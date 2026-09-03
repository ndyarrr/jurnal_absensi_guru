<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class WaBotService
{
    protected string $baseUrl;
    protected int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.wa_bot.url', 'http://127.0.0.1:3000');
        $this->timeout = 5; // seconds
    }

    /**
     * Ambil status real-time koneksi bot WhatsApp
     */
    public function getStatus(): array
    {
        try {
            $response = Http::timeout($this->timeout)->get("{$this->baseUrl}/api/status");
            if ($response->successful()) {
                $data = $response->json();
                $data['process'] = $this->getProcessInfo();
                return $data;
            }
        } catch (\Exception $e) {
            Log::warning("WaBotService: Bot offline or unreachable. " . $e->getMessage());
        }

        return [
            'success' => false,
            'status' => 'offline',
            'user' => null,
            'qrCode' => null,
            'pairingCode' => null,
            'process' => $this->getProcessInfo(),
            'message' => 'Service bot WhatsApp (Node.js) sedang offline atau belum dijalankan.'
        ];
    }

    /**
     * Deteksi sistem operasi berjalan.
     */
    protected function isWindows(): bool
    {
        return defined('PHP_OS_FAMILY') && PHP_OS_FAMILY === 'Windows';
    }

    /**
     * Cari letak binary PM2 yang valid lintas platform.
     *
     * Prioritas:
     * 1. Nilai config WA_BOT_PM2_BIN / wa_bot.pm2_bin (jika diisi).
     * 2. Otomatis lengkapi ekstensi ".cmd" di Windows bila path berupa file tanpa ekstensi.
     * 3. Default "pm2" (Linux/macOS) atau "pm2.cmd" (Windows, lewat PATH).
     */
    protected function resolvePm2Binary(): string
    {
        $bin = trim((string) config('services.wa_bot.pm2_bin'));

        if ($bin === '') {
            return $this->isWindows() ? 'pm2.cmd' : 'pm2';
        }

        if ($this->isWindows()) {
            $hasExtension = (bool) preg_match('/\.(cmd|bat|exe|ps1)$/i', $bin);
            $looksLikePath = str_contains($bin, '\\')
                || str_contains($bin, '/')
                || is_file($bin);

            if (!$hasExtension && $looksLikePath && is_file($bin . '.cmd')) {
                return $bin . '.cmd';
            }
        }

        return $bin;
    }

    /**
     * Cek apakah sebuah perintah tersedia di sistem (path file atau where/which).
     */
    protected function commandExists(string $bin): bool
    {
        $looksLikePath = str_contains($bin, '\\')
            || str_contains($bin, '/')
            || str_ends_with(strtolower($bin), ['.cmd', '.bat', '.exe', '.ps1']);

        if ($looksLikePath) {
            if (is_file($bin)) {
                return true;
            }

            return $this->isWindows()
                && !preg_match('/\.(cmd|bat|exe|ps1)$/i', $bin)
                && is_file($bin . '.cmd');
        }

        try {
            $checker = $this->isWindows() ? 'where' : 'which';
            $process = new Process([$checker, $bin]);
            $process->setTimeout(10);
            $process->run();

            return $process->isSuccessful();
        } catch (\Throwable $e) {
            return false;
        }
    }

    /**
     * Jalankan perintah PM2 dari folder bot/
     */
    protected function runPm2(string $command): array
    {
        $bin = $this->resolvePm2Binary();
        $botDir = config('services.wa_bot.bot_dir', base_path('bot'));
        $pm2Home = config('services.wa_bot.pm2_home');

        $process = Process::fromShellCommandline(trim(escapeshellarg($bin) . ' ' . $command));
        $process->setWorkingDirectory($botDir);
        $process->setTimeout(30);

        if ($pm2Home) {
            $process->setEnv(['PM2_HOME' => $pm2Home]);
        }

        $process->run();

        return [
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
            'exitCode' => $process->getExitCode(),
            'command' => $bin . ' ' . $command,
        ];
    }

    /**
     * Ambil informasi status proses bot dari PM2
     */
    public function getProcessInfo(): array
    {
        $result = $this->runPm2('jlist');

        if (!$result['success']) {
            $bin = $this->resolvePm2Binary();
            $available = $this->commandExists($bin);

            return [
                'available' => $available,
                'registered' => false,
                'status' => 'na',
                'pid' => null,
                'restarts' => null,
                'uptime' => null,
                'uptimeHuman' => null,
                'command' => $result['command'],
                'message' => $available
                    ? trim($result['error'] ?: $result['output'])
                    : "Binary PM2 ('{$bin}') tidak ditemukan. Install dengan 'npm install -g pm2' atau atur WA_BOT_PM2_BIN di .env.",
            ];
        }

        $appName = config('services.wa_bot.pm2_app_name', 'wa-bot');
        $app = null;

        try {
            $list = json_decode($result['output'], true);
            if (is_array($list)) {
                foreach ($list as $entry) {
                    if (($entry['name'] ?? '') === $appName) {
                        $app = $entry;
                        break;
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning("WaBotService: Gagal parse pm2 jlist. " . $e->getMessage());
        }

        if (!$app) {
            return [
                'available' => true,
                'registered' => false,
                'status' => 'na',
                'pid' => null,
                'restarts' => null,
                'uptime' => null,
                'uptimeHuman' => null,
                'command' => $result['command'],
            ];
        }

        $status = $app['pm2_env']['status'] ?? 'unknown';
        $pmUptime = $app['pm2_env']['pm_uptime'] ?? 0;
        $uptimeHuman = '';

        if ($pmUptime && $status === 'online') {
            $seconds = max(1, (int) (ceil((time() * 1000 - $pmUptime) / 1000)));
            $uptimeHuman = $seconds >= 3600
                ? floor($seconds / 3600) . ' jam ' . floor(($seconds % 3600) / 60) . ' mnt'
                : floor($seconds / 60) . ' menit';
        }

        return [
            'available' => true,
            'registered' => true,
            'status' => $status,
            'pid' => $app['pid'] ?? null,
            'restarts' => $app['pm2_env']['restart_time'] ?? 0,
            'uptime' => $pmUptime ?: null,
            'uptimeHuman' => $uptimeHuman ?: null,
            'command' => $result['command'],
        ];
    }

    /**
     * Nyalakan proses bot via PM2 (setara npm start)
     */
    public function startProcess(): array
    {
        $info = $this->getProcessInfo();

        if (!$info['available']) {
            return [
                'success' => false,
                'message' => $info['message'] ?? 'PM2 tidak ditemukan di server. Pasang PM2 atau atur WA_BOT_PM2_BIN di .env.',
            ];
        }

        $appName = config('services.wa_bot.pm2_app_name', 'wa-bot');

        if ($info['registered'] && $info['status'] === 'online') {
            return ['success' => true, 'message' => 'Bot WhatsApp sudah berjalan (online).'];
        }

        $command = $info['registered']
            ? "start {$appName}"
            : "start index.js --name {$appName}";

        $result = $this->runPm2($command);

        return [
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Proses bot WhatsApp berhasil dihidupkan (pm2 start).'
                : 'Gagal menghidupkan bot WhatsApp: ' . trim($result['error'] ?: $result['output']),
        ];
    }

    /**
     * Matikan proses bot via PM2 (benar-benar stop, tidak auto-reconnect)
     */
    public function stopProcess(): array
    {
        $info = $this->getProcessInfo();

        if (!$info['available']) {
            return [
                'success' => false,
                'message' => $info['message'] ?? 'PM2 tidak ditemukan di server. Pasang PM2 atau atur WA_BOT_PM2_BIN di .env.',
            ];
        }

        $appName = config('services.wa_bot.pm2_app_name', 'wa-bot');

        if (!$info['registered'] || ($info['status'] !== 'online' && $info['status'] !== 'errored')) {
            return ['success' => true, 'message' => 'Bot WhatsApp sedang tidak berjalan.'];
        }

        $result = $this->runPm2("stop {$appName}");

        return [
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Proses bot WhatsApp berhasil dimatikan (pm2 stop).'
                : 'Gagal mematikan bot WhatsApp: ' . trim($result['error'] ?: $result['output']),
        ];
    }

    /**
     * Minta Pairing Code 8 digit berdasarkan nomor WhatsApp
     */
    public function getPairingCode(string $phoneNumber): array
    {
        try {
            $response = Http::timeout(15)->post("{$this->baseUrl}/api/pair-code", [
                'phoneNumber' => $phoneNumber,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal terhubung ke service bot WA: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Kirim pesan teks WhatsApp ke nomor tujuan
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/api/send", [
                'phone' => $phone,
                'message' => $message,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan via Bot WA: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Logout & bersihkan sesi bot WA
     */
    public function logout(): array
    {
        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/api/logout");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal logout bot: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Reconnect bot WA
     */
    public function reconnect(): array
    {
        try {
            $response = Http::timeout(10)->post("{$this->baseUrl}/api/reconnect");
            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal rekoneksi bot: ' . $e->getMessage(),
            ];
        }
    }
}
