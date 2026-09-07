@extends('layouts.guru_mengajar')

@section('title', 'Riwayat Pengajuan Izin Guru')
@section('page-title', 'Riwayat & Pengajuan Izin')
@section('page-subtitle', 'Ajukan permohonan izin kerja (sakit, dinas, cuti, dll.) dan hubungi Waka/Kepsek via WhatsApp.')

@section('content')
    <div style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
        <a href="{{ route('guru-mengajar.izin.create') }}" class="gm-btn gm-btn-navy" style="padding: 10px 18px; border-radius: 10px; font-weight: 700;">
            <i class="fa-solid fa-plus"></i> Buat Pengajuan Izin Baru
        </a>
    </div>

    <div class="gm-card" style="padding: 20px;">
        <div class="gm-card-body">
            @if($izinList->isEmpty())
                <div style="text-align: center; padding: 40px 20px;">
                    <div style="width: 64px; height: 64px; background: #f1f5f9; color: #64748b; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px; font-size: 1.8rem;">
                        <i class="fa-solid fa-envelope-open-text"></i>
                    </div>
                    <h3 style="margin: 0 0 6px 0; color: #1e293b; font-weight: 800;">Belum Ada Pengajuan Izin</h3>
                    <p style="color: #64748b; margin: 0 0 20px 0; font-size: 0.9rem;">Anda belum pernah membuat permohonan izin kerja.</p>
                    <a href="{{ route('guru-mengajar.izin.create') }}" class="gm-btn gm-btn-navy">
                        <i class="fa-solid fa-plus"></i> Ajukan Surat Izin Sekarang
                    </a>
                </div>
            @else
                <div class="gm-table-responsive">
                    <table class="gm-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Jenis / Alasan Izin</th>
                                <th>Durasi Tanggal</th>
                                <th>Keterangan Rincian</th>
                                <th style="text-align: center;">Bukti Dokumen</th>
                                <th style="text-align: center;">Status Approval</th>
                                <th style="text-align: center; width: 170px;">Notifikasi WA</th>
                                <th style="text-align: center; width: 80px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($izinList as $index => $item)
                                @php
                                    $namaGuru = $guru->nama_guru ?? Auth::user()->name;
                                    $kategoriStr = $item->kategori_label;
                                    $tglM = $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-';
                                    $tglS = $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-';
                                    $tglText = ($tglM === $tglS) ? $tglM : "{$tglM} s/d {$tglS}";
                                    $buktiStr = $item->bukti_surat_url ? $item->bukti_surat_url : '-';

                                    $waPesan = \App\Models\WaTemplate::renderMessage('izin_guru', [
                                        'nama_guru' => $namaGuru,
                                        'jenis_izin' => $kategoriStr,
                                        'tanggal' => $tglText,
                                        'keterangan' => $item->alasan_izin,
                                        'link_dokumen_bukti_izin' => $buktiStr,
                                        'link_persetujuan_waka_kepsek' => $item->approval_url,
                                    ]);
                                    $waEnc = urlencode($waPesan);

                                    $cleanPhone = function($num) {
                                        $p = preg_replace('/[^0-9]/', '', $num ?? '');
                                        if (str_starts_with($p, '0')) { $p = '62' . substr($p, 1); }
                                        return $p;
                                    };

                                    $wakaNum = $cleanPhone($nomorWaka ?? '');
                                    $kepsekNum = $cleanPhone($nomorKepsek ?? '');
                                @endphp
                                <tr>
                                    <td>{{ $izinList->firstItem() + $index }}</td>
                                    <td>
                                        @php
                                            $badgeClass = match($item->kategori_izin) {
                                                'sakit' => 'background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;',
                                                'dinas', 'dinas_luar' => 'background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;',
                                                'cuti' => 'background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;',
                                                'acara_keluarga', 'urusan_keluarga' => 'background: #faf5ff; color: #9333ea; border: 1px solid #e9d5ff;',
                                                default => 'background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;',
                                            };
                                        @endphp
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 6px; font-weight: 800; font-size: 0.8rem; {{ $badgeClass }}">
                                            {{ $item->kategori_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 700; color: #1e293b;">
                                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->translatedFormat('d M Y') }}
                                            @if($item->tanggal_mulai != $item->tanggal_selesai)
                                                <span style="color: #64748b;">s/d</span> {{ \Carbon\Carbon::parse($item->tanggal_selesai)->translatedFormat('d M Y') }}
                                            @endif
                                        </div>
                                        <small style="color: #64748b; font-weight: 600;">
                                            ({{ \Carbon\Carbon::parse($item->tanggal_mulai)->diffInDays(\Carbon\Carbon::parse($item->tanggal_selesai)) + 1 }} hari)
                                        </small>
                                    </td>
                                    <td>
                                        <span style="color: #334155; font-size: 0.875rem;">{{ Str::limit($item->alasan_izin, 80) }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($item->bukti_surat_url)
                                            <a href="{{ $item->bukti_surat_url }}" target="_blank" class="gm-btn gm-btn-outline" style="padding: 4px 10px; font-size: 0.78rem;" title="Lihat foto/dokumen bukti">
                                                <i class="fa-solid fa-paperclip"></i> Lihat Bukti
                                            </a>
                                        @else
                                            <span style="color: #94a3b8; font-size: 0.8rem; font-style: italic;">Tidak ada</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        @if($item->status_approval === 'pending')
                                            <span style="background: #fefce8; color: #ca8a04; border: 1px solid #fef08a; padding: 4px 10px; border-radius: 20px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fa-solid fa-clock"></i> Menunggu
                                            </span>
                                        @elseif(in_array($item->status_approval, ['approved', 'disetujui', 'disetujui_piket', 'disetujui_waka', 'disetujui_kepsek'], true))
                                            <span style="background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; padding: 4px 10px; border-radius: 20px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fa-solid fa-circle-check"></i> Disetujui
                                            </span>
                                        @else
                                            <span style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 20px; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: center;">
                                        <div style="display: flex; gap: 4px; justify-content: center; flex-wrap: wrap;">
                                            <a href="{{ $wakaNum ? 'https://wa.me/' . $wakaNum . '?text=' . $waEnc : 'https://api.whatsapp.com/send?text=' . $waEnc }}" target="_blank" class="gm-btn" style="background: #25d366; color: white; border: none; padding: 4px 8px; font-size: 0.73rem; border-radius: 6px; text-decoration: none;" title="Kirim Pesan WA Izin ke Waka">
                                                <i class="fa-brands fa-whatsapp"></i> Waka
                                            </a>
                                            <a href="{{ $kepsekNum ? 'https://wa.me/' . $kepsekNum . '?text=' . $waEnc : 'https://api.whatsapp.com/send?text=' . $waEnc }}" target="_blank" class="gm-btn" style="background: #075e54; color: white; border: none; padding: 4px 8px; font-size: 0.73rem; border-radius: 6px; text-decoration: none;" title="Kirim Pesan WA Izin ke Kepsek">
                                                <i class="fa-brands fa-whatsapp"></i> Kepsek
                                            </a>
                                        </div>
                                    </td>
                                    <td style="text-align: center;">
                                        @if($item->status_approval === 'pending')
                                            <form action="{{ route('guru-mengajar.izin.destroy', $item->id_izin_guru) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengajuan izin ini?')" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; padding: 5px 10px; border-radius: 8px; font-size: 0.78rem; font-weight: 700; cursor: pointer;" title="Batalkan Pengajuan">
                                                    <i class="fa-solid fa-trash-can"></i> Hapus
                                                </button>
                                            </form>
                                        @else
                                            <span style="color: #cbd5e1; font-size: 0.8rem;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    {{ $izinList->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection
