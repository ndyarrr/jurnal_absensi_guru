<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WaSetting;
use App\Models\WaTemplate;
use App\Models\WaRecipient;
use App\Services\WaBotService;

class PengaturanWaController extends Controller
{
    protected WaBotService $waBotService;

    public function __construct(WaBotService $waBotService)
    {
        $this->waBotService = $waBotService;
    }

    /**
     * Halaman Utama Pengaturan WhatsApp dengan Tabbed Navigation
     */
    public function index(Request $request)
    {
        $activeTab = $request->query('tab', 'bot-status');

        $botInfo = $this->waBotService->getStatus();
        $settings = WaSetting::all()->pluck('value', 'key')->toArray();
        $templates = WaTemplate::orderBy('id', 'asc')->get();
        $recipients = WaRecipient::orderBy('id', 'asc')->get();

        return view('admin.pengaturan_wa.index', compact('activeTab', 'botInfo', 'settings', 'templates', 'recipients'));
    }

    /**
     * Endpoint AJAX untuk membaca status real-time bot
     */
    public function apiStatus()
    {
        $botInfo = $this->waBotService->getStatus();
        return response()->json($botInfo);
    }

    /**
     * Meminta Pairing Code 8 digit
     */
    public function requestPairingCode(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|string|min:9',
        ]);

        $result = $this->waBotService->getPairingCode($request->phone_number);

        if (isset($result['success']) && $result['success']) {
            // Simpan nomor bot ke settings
            WaSetting::setKey('bot_phone_number', $request->phone_number, 'bot', 'Nomor HP Bot WA');
            return response()->json([
                'success' => true,
                'code' => $result['code'] ?? '',
                'message' => 'Kode Pairing berhasil dibuat! Masukkan kode ini pada aplikasi WhatsApp di ponsel Anda.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['message'] ?? 'Gagal membuat kode pairing.',
        ], 400);
    }

    /**
     * Logout Bot WhatsApp
     */
    public function logoutBot()
    {
        $result = $this->waBotService->logout();
        if ($result['success'] ?? false) {
            return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
                ->with('success', 'Session WhatsApp Bot berhasil dibersihkan.');
        }

        return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
            ->with('error', $result['message'] ?? 'Gagal logout bot.');
    }

    /**
     * Reconnect Bot WhatsApp
     */
    public function reconnectBot()
    {
        $result = $this->waBotService->reconnect();
        return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
            ->with('success', 'Memulai ulang koneksi WhatsApp Bot...');
    }

    /**
     * Nyalakan proses Bot WhatsApp via PM2 (setara npm start)
     */
    public function startBot()
    {
        $result = $this->waBotService->startProcess();

        if ($result['success'] ?? false) {
            return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
                ->with('success', $result['message']);
        }

        return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
            ->with('error', $result['message'] ?? 'Gagal menghidupkan bot.');
    }

    /**
     * Matikan proses Bot WhatsApp via PM2
     */
    public function stopBot()
    {
        $result = $this->waBotService->stopProcess();

        if ($result['success'] ?? false) {
            return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
                ->with('success', $result['message']);
        }

        return redirect()->route('pengaturan-wa.index', ['tab' => 'bot-status'])
            ->with('error', $result['message'] ?? 'Gagal mematikan bot.');
    }

    /**
     * Simpan Pengaturan Umum & Reminder
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'wa_enabled' => 'nullable|in:0,1',
            'reminder_jurnal_enabled' => 'nullable|in:0,1',
            'reminder_before_minutes' => 'required|integer|min:1|max:120',
            'target_roles' => 'array',
        ]);

        WaSetting::setKey('wa_enabled', $request->has('wa_enabled') ? '1' : '0', 'general', 'Aktifkan/Nonaktifkan Notifikasi WA');
        WaSetting::setKey('reminder_jurnal_enabled', $request->has('reminder_jurnal_enabled') ? '1' : '0', 'reminder', 'Aktifkan Pengingat Jurnal');
        WaSetting::setKey('reminder_before_minutes', $request->reminder_before_minutes, 'reminder', 'Waktu pengingat sebelum jam selesai');
        
        if ($request->has('target_roles')) {
            WaSetting::setKey('notification_target_roles', $request->target_roles, 'general', 'Role target penerima default');
        }

        return redirect()->route('pengaturan-wa.index', ['tab' => 'settings'])
            ->with('success', 'Pengaturan notifikasi dan reminder berhasil diperbarui.');
    }

    /**
     * Tambah Template Pesan Baru
     */
    public function storeTemplate(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:wa_templates,kode',
            'nama' => 'required|string|max:255',
            'kategori' => 'required|string',
            'format_pesan' => 'required|string',
        ]);

        WaTemplate::create([
            'kode' => strtolower(str_replace(' ', '_', $request->kode)),
            'nama' => $request->nama,
            'kategori' => $request->kategori,
            'format_pesan' => $request->format_pesan,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('pengaturan-wa.index', ['tab' => 'templates'])
            ->with('success', 'Template pesan WhatsApp berhasil ditambahkan.');
    }

    /**
     * Update Template Pesan
     */
    public function updateTemplate(Request $request, $id)
    {
        $template = WaTemplate::findOrFail($id);

        // Support both JSON body (from fetch AJAX) and regular form POST
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $nama        = trim($data['nama'] ?? '');
        $kode        = !empty($data['kode']) ? trim($data['kode']) : $template->kode;
        $kategori    = $data['kategori'] ?? $template->kategori;
        $formatPesan = $data['format_pesan'] ?? '';
        $isActive    = isset($data['is_active']) ? (bool) $data['is_active'] : true;

        if (!$nama || !$formatPesan) {
            if ($request->isJson()) {
                return response()->json(['success' => false, 'message' => 'Nama dan Format Pesan wajib diisi.'], 422);
            }
            return back()->withErrors(['nama' => 'Nama wajib diisi.']);
        }

        $template->update([
            'nama'         => $nama,
            'kode'         => $kode,
            'kategori'     => $kategori,
            'format_pesan' => $formatPesan,
            'is_active'    => $isActive,
        ]);

        if ($request->isJson()) {
            return response()->json(['success' => true, 'message' => 'Template berhasil diperbarui.']);
        }

        return redirect()->route('pengaturan-wa.index', ['tab' => 'templates'])
            ->with('success', 'Template pesan "' . $template->nama . '" berhasil diperbarui.');
    }

    /**
     * Hapus Template Pesan
     */
    public function destroyTemplate($id)
    {
        $template = WaTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('pengaturan-wa.index', ['tab' => 'templates'])
            ->with('success', 'Template pesan berhasil dihapus.');
    }

    /**
     * Tambah Penerima Khusus
     */
    public function storeRecipient(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'required|string',
            'peran' => 'required|string',
        ]);

        WaRecipient::create([
            'nama' => $request->nama,
            'nomor_wa' => $request->nomor_wa,
            'peran' => $request->peran,
            'terima_notifikasi' => $request->has('terima_notifikasi'),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('pengaturan-wa.index', ['tab' => 'recipients'])
            ->with('success', 'Penerima notifikasi baru berhasil ditambahkan.');
    }

    /**
     * Update Penerima Khusus
     */
    public function updateRecipient(Request $request, $id)
    {
        $recipient = WaRecipient::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'nomor_wa' => 'required|string',
            'peran' => 'required|string',
        ]);

        $recipient->update([
            'nama' => $request->nama,
            'nomor_wa' => $request->nomor_wa,
            'peran' => $request->peran,
            'terima_notifikasi' => $request->has('terima_notifikasi'),
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('pengaturan-wa.index', ['tab' => 'recipients'])
            ->with('success', 'Data penerima "' . $recipient->nama . '" berhasil diubah.');
    }

    /**
     * Hapus Penerima Khusus
     */
    public function destroyRecipient($id)
    {
        $recipient = WaRecipient::findOrFail($id);
        $recipient->delete();

        return redirect()->route('pengaturan-wa.index', ['tab' => 'recipients'])
            ->with('success', 'Data penerima berhasil dihapus.');
    }

    /**
     * Uji Coba Kirim Pesan WA
     */
    public function sendTestMessage(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $result = $this->waBotService->sendMessage($request->phone, $request->message);

        if ($result['success'] ?? false) {
            return redirect()->back()->with('success', 'Pesan uji coba berhasil terkirim ke ' . $request->phone);
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Gagal mengirim pesan uji coba.');
    }
}
