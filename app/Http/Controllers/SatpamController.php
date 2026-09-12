<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\JadwalPiket;
use App\Models\LaporanKejadianSiswa;
use App\Models\PermohonanIzin;
use App\Models\Siswa;
use App\Models\WaSetting;
use App\Models\WaTemplate;
use App\Services\WaBotService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SatpamController extends Controller
{
    /* ==========================================================================
       1. DASHBOARD
       ========================================================================== */
    public function dashboard(Request $request)
    {
        $today = Carbon::now('Asia/Jakarta')->toDateString();

        $izinHariIni = PermohonanIzin::with(['siswa.kelas'])
            ->where('tipe_pemohon', 'siswa')
            ->whereDate('tanggal_mulai', $today)
            ->orderByDesc('jam_keluar')
            ->get();

        $totalSiswa = Siswa::count();
        $sedangIzinKeluar = $izinHariIni->filter(fn ($i) => $i->jam_keluar && !$i->jam_kembali_aktual)->count();
        $terlambat = $izinHariIni->filter(fn ($i) => $i->status_gerbang === 'kadaluwarsa')->count();
        $laporanHariIni = LaporanKejadianSiswa::whereDate('created_at', $today)->count();

        // Aktivitas gerbang terbaru: gabungan izin keluar/masuk hari ini, urut terbaru
        $aktivitasGerbang = $izinHariIni->take(6)->map(function ($izin) {
            $isKembali = (bool) $izin->jam_kembali_aktual;
            return [
                'nama_siswa' => optional($izin->siswa)->nama_siswa ?? '-',
                'kelas' => $this->kelasLabel(optional($izin->siswa)->kelas),
                'aktivitas' => $isKembali ? 'Kembali ke gerbang' : 'Izin keluar - ' . $izin->jenis_izin,
                'keterangan' => $izin->alasan,
                'waktu' => $isKembali
                    ? optional($izin->jam_kembali_aktual ? Carbon::parse($izin->jam_kembali_aktual) : null)?->format('H:i')
                    : optional($izin->jam_keluar ? Carbon::parse($izin->jam_keluar) : null)?->format('H:i'),
            ];
        });

        // Izin aktif butuh verifikasi (belum dicatat jam keluarnya oleh satpam)
        $izinButuhVerifikasi = $izinHariIni->filter(fn ($i) => !$i->jam_keluar)->take(5);

        $stats = [
            'siswa_sudah_masuk' => max($totalSiswa - $sedangIzinKeluar, 0),
            'sedang_izin_keluar' => $sedangIzinKeluar,
            'terlambat' => $terlambat,
            'laporan_kejadian' => $laporanHariIni,
        ];

        return view('satpam.dashboard', compact('stats', 'aktivitasGerbang', 'izinButuhVerifikasi'));
    }

    /* ==========================================================================
       2. CEK IZIN SISWA
       ========================================================================== */
    public function cekIzin(Request $request)
    {
        $query = PermohonanIzin::with(['siswa.kelas'])->where('tipe_pemohon', 'siswa');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('siswa', function ($q) use ($search) {
                $q->where('nama_siswa', 'like', "%{$search}%");
            });
        }

        $daftarIzin = $query->orderByDesc('tanggal_mulai')->orderByDesc('jam_keluar')->paginate(15)->withQueryString();

        // Filter status_gerbang dilakukan di collection karena itu accessor turunan, bukan kolom DB
        $filterStatus = $request->input('status');
        if ($filterStatus && $filterStatus !== 'semua') {
            $daftarIzin->setCollection(
                $daftarIzin->getCollection()->filter(fn ($i) => $i->status_gerbang === $filterStatus)->values()
            );
        }

        return view('satpam.cek_izin', compact('daftarIzin', 'filterStatus'));
    }

    /**
     * Satpam mencatat jam keluar siswa (submit dari modal di halaman Cek Izin Siswa).
     */
    public function catatKeluar(Request $request, PermohonanIzin $izin)
    {
        $validated = $request->validate([
            'perkiraan_kembali' => 'nullable|date_format:H:i',
        ]);

        $izin->jam_keluar = Carbon::now('Asia/Jakarta')->format('H:i:s');
        $izin->perkiraan_kembali = $validated['perkiraan_kembali'] ?? null;
        $izin->dicatat_oleh_user_id = auth()->id();
        $izin->save();

        return redirect()->route('satpam.cek-izin')->with('success', 'Jam keluar siswa berhasil dicatat.');
    }

    /**
     * Satpam mencatat siswa sudah kembali ke sekolah.
     */
    public function catatKembali(Request $request, PermohonanIzin $izin)
    {
        $izin->jam_kembali_aktual = Carbon::now('Asia/Jakarta')->format('H:i:s');
        $izin->save();

        return redirect()->route('satpam.cek-izin')->with('success', 'Siswa tercatat sudah kembali ke sekolah.');
    }

    /* ==========================================================================
       3. LAPOR SISWA
       ========================================================================== */
    public function laporSiswaForm(Request $request)
    {
        $siswaList = Siswa::with('kelas.jurusan')->orderBy('nama_siswa')->get();
        return view('satpam.lapor_siswa', compact('siswaList'));
    }
    
    public function storeLaporSiswa(Request $request, WaBotService $waBotService)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswa,id_siswa',
            'jenis_kejadian' => 'required|in:terlambat_kembali,keluar_tanpa_izin,pelanggaran_tata_tertib,lainnya',
            'catatan_kejadian' => 'required|string|max:1000',
            'kirim_ke_wali_kelas' => 'nullable|boolean',
            'kirim_ke_guru_piket' => 'nullable|boolean',
            'aksi' => 'required|in:kirim,draft',
        ]);

        $siswa = Siswa::with('kelas')->findOrFail($validated['id_siswa']);

        $laporan = LaporanKejadianSiswa::create([
            'id_siswa' => $siswa->id_siswa,
            'id_kelas' => $siswa->id_kelas,
            'jenis_kejadian' => $validated['jenis_kejadian'],
            'catatan_kejadian' => $validated['catatan_kejadian'],
            'id_user_pelapor' => auth()->id(),
            'kirim_ke_wali_kelas' => $request->boolean('kirim_ke_wali_kelas'),
            'kirim_ke_guru_piket' => $request->boolean('kirim_ke_guru_piket'),
            'status' => $validated['aksi'] === 'kirim' ? 'terkirim' : 'draft',
        ]);

        if ($validated['aksi'] === 'kirim') {
            $this->kirimLaporanViaWa($laporan, $siswa, $waBotService);
        }

        $pesan = $validated['aksi'] === 'kirim'
            ? 'Laporan berhasil dikirim.'
            : 'Laporan berhasil disimpan sebagai draft.';

        return redirect()->route('satpam.lapor-siswa')->with('success', $pesan);
    }

    private function kirimLaporanViaWa(LaporanKejadianSiswa $laporan, Siswa $siswa, WaBotService $waBotService): void
    {
        try {
            $waEnabled = WaSetting::getByKey('wa_enabled', '1') === '1';
            $botStatus = $waBotService->getStatus();
            $isBotOnline = $waEnabled && isset($botStatus['status']) && $botStatus['status'] === 'connected';

            if (!$isBotOnline) {
                return;
            }

            $penerima = collect();

            if ($laporan->kirim_ke_wali_kelas && $siswa->kelas && $siswa->kelas->id_guru_wali) {
                $wali = Guru::find($siswa->kelas->id_guru_wali);
                if ($wali && !empty($wali->no_hp)) {
                    $penerima->push($wali->no_hp);
                }
            }

            if ($laporan->kirim_ke_guru_piket) {
                $hariIni = Carbon::now('Asia/Jakarta')->translatedFormat('l');
                $piket = JadwalPiket::where('hari', $hariIni)->first();
                if ($piket) {
                    $guruPiket = Guru::find($piket->id_guru);
                    if ($guruPiket && !empty($guruPiket->no_hp)) {
                        $penerima->push($guruPiket->no_hp);
                    }
                }
            }

            if ($penerima->isEmpty()) {
                return;
            }

            $pesan = WaTemplate::renderMessage('laporan_kejadian_siswa', [
                'nama_siswa' => $siswa->nama_siswa,
                'nama_kelas' => $this->kelasLabel($siswa->kelas),
                'jenis_kejadian' => $laporan->jenis_label,
                'catatan_kejadian' => $laporan->catatan_kejadian,
                'nama_pelapor' => auth()->user()->name,
                'tanggal' => Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i'),
            ]);

            $terkirim = false;
            foreach ($penerima->unique() as $noHp) {
                $waBotService->sendMessage($noHp, $pesan);
                $terkirim = true;
            }

            if ($terkirim) {
                $laporan->update(['terkirim_wa' => true]);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Gagal kirim laporan kejadian via WA: ' . $e->getMessage());
        }
    }

    /* ==========================================================================
       Helper
       ========================================================================== */
    private function kelasLabel($kelas): string
    {
        if (!$kelas) {
            return '-';
        }

        return trim($kelas->tingkat . ' ' . optional($kelas->jurusan)->kode_jurusan . ' ' . $kelas->rombel);
    }
}