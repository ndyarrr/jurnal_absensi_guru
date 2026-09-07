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
        $this->timeout = 2; // seconds (fast fail when offline)
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
     * Konversi path relatif menjadi path absolut terhadap base_path() project.
     * Jika sudah berupa path absolut, kembalikan apa adanya.
     */
    protected function makeAbsolutePath(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return '';
        }

        $isAbsolute = str_starts_with($path, '/')
            || str_starts_with($path, '\\')
            || (strlen($path) >= 2 && $path[1] === ':');

        if ($isAbsolute) {
            return $path;
        }

        return base_path($path);
    }

    /**
     * Cari letak binary PM2 yang valid lintas platform.
     *
     * Prioritas:
     * 1. Nilai config WA_BOT_PM2_BIN / wa_bot.pm2_bin (jika diisi).
     * 2. Auto-detect PM2 lokal dari bot/node_modules/.bin/ atau node_modules/.bin/.
     * 3. Default "pm2" (Linux/macOS) atau "pm2.cmd" / APPDATA (Windows).
     */
    protected function resolvePm2Binary(): string
    {
        $isWin = $this->isWindows();
        $binConfig = trim((string) config('services.wa_bot.pm2_bin'));

        if ($binConfig !== '') {
            $absPath = $this->makeAbsolutePath($binConfig);

            if (str_contains($binConfig, '/') || str_contains($binConfig, '\\') || is_file($absPath)) {
                if ($isWin) {
                    if (!preg_match('/\.(cmd|bat|exe)$/i', $absPath)) {
                        if (is_file($absPath . '.cmd')) {
                            return $absPath . '.cmd';
                        }
                        if (is_file($absPath . '.exe')) {
                            return $absPath . '.exe';
                        }
                        if (is_file($absPath . '.bat')) {
                            return $absPath . '.bat';
                        }
                    }
                }
                if (is_file($absPath)) {
                    return $absPath;
                }
            } else {
                if ($isWin && !preg_match('/\.(cmd|bat|exe)$/i', $binConfig)) {
                    return $binConfig . '.cmd';
                }
                return $binConfig;
            }
        }

        // Auto-detect PM2 lokal di node_modules project
        $localCandidates = [
            'bot/node_modules/.bin/pm2',
            'node_modules/.bin/pm2',
        ];

        foreach ($localCandidates as $candidate) {
            $candidateAbs = base_path($candidate);
            if ($isWin) {
                if (is_file($candidateAbs . '.cmd')) {
                    return $candidateAbs . '.cmd';
                }
                if (is_file($candidateAbs . '.exe')) {
                    return $candidateAbs . '.exe';
                }
            }
            if (is_file($candidateAbs)) {
                return $candidateAbs;
            }
        }

        if ($isWin) {
            $appData = getenv('APPDATA');
            if ($appData) {
                $pm2Win = $appData . DIRECTORY_SEPARATOR . 'npm' . DIRECTORY_SEPARATOR . 'pm2.cmd';
                if (is_file($pm2Win)) {
                    return $pm2Win;
                }
            }
            return 'pm2.cmd';
        }

        return 'pm2';
    }

    /**
     * Cari letak binary Node.js yang valid secara otomatis atau dari config.
     */
    protected function resolveNodeBinary(): ?string
    {
        $isWin = $this->isWindows();
        $binConfig = trim((string) config('services.wa_bot.node_bin'));

        if ($binConfig !== '') {
            $absPath = $this->makeAbsolutePath($binConfig);
            if (is_file($absPath)) {
                return $absPath;
            }
            if ($isWin && !preg_match('/\.(exe|cmd|bat)$/i', $absPath) && is_file($absPath . '.exe')) {
                return $absPath . '.exe';
            }
        }

        // Cek via command 'where' (Windows) atau 'which' (Linux)
        try {
            $checker = $isWin ? 'where node' : 'which node';
            $process = Process::fromShellCommandline($checker);
            $process->setTimeout(5);
            $process->run();
            if ($process->isSuccessful()) {
                $lines = array_filter(array_map('trim', explode("\n", $process->getOutput())));
                foreach ($lines as $line) {
                    if (is_file($line)) {
                        return $line;
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignore failure
        }

        // Candidate lokasi umum Node.js
        $candidates = [];
        if ($isWin) {
            $programFiles = getenv('ProgramFiles') ?: 'C:\\Program Files';
            $programFilesX86 = getenv('ProgramFiles(x86)') ?: 'C:\\Program Files (x86)';
            $localAppData = getenv('LOCALAPPDATA');
            $appData = getenv('APPDATA');

            $candidates = [
                'D:\\nodejs\\node.exe',
                'C:\\nodejs\\node.exe',
                $programFiles . '\\nodejs\\node.exe',
                $programFilesX86 . '\\nodejs\\node.exe',
            ];
            if ($localAppData) {
                $candidates[] = $localAppData . '\\Programs\\node\\node.exe';
            }
            if ($appData) {
                $candidates[] = $appData . '\\npm\\node.exe';
            }
            $nvmHome = getenv('NVM_HOME');
            if ($nvmHome) {
                $candidates[] = rtrim($nvmHome, '\\/') . '\\node.exe';
            }
            $nvmSymlink = getenv('NVM_SYMLINK');
            if ($nvmSymlink) {
                $candidates[] = rtrim($nvmSymlink, '\\/') . '\\node.exe';
            }
        } else {
            $candidates = [
                '/usr/local/bin/node',
                '/usr/bin/node',
                '/bin/node',
            ];
            $home = getenv('HOME');
            if ($home) {
                $nvmNodes = glob($home . '/.nvm/versions/node/*/bin/node');
                if (is_array($nvmNodes)) {
                    $candidates = array_merge($candidates, $nvmNodes);
                }
            }
        }

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    /**
     * Dapatkan Environment Variables untuk Symfony Process (memastikan PATH memuat lokasi Node & PM2).
     */
    protected function getEnvironmentVariables(): array
    {
        $isWin = $this->isWindows();
        $sep = $isWin ? ';' : ':';

        // Ambil PATH yang ada di environment PHP
        $currentPath = getenv('PATH') ?: (getenv('Path') ?: ($_SERVER['PATH'] ?? ($_SERVER['Path'] ?? '')));
        $pathDirs = array_filter(explode($sep, (string) $currentPath), fn($d) => trim($d) !== '');

        $extraDirs = [];

        // 1. Lokasi Node.js dari resolveNodeBinary
        $nodeBin = $this->resolveNodeBinary();
        if ($nodeBin && is_file($nodeBin)) {
            $extraDirs[] = dirname($nodeBin);
        }

        // 2. Lokasi PM2 dari resolvePm2Binary
        $pm2Bin = $this->resolvePm2Binary();
        if (is_file($pm2Bin)) {
            $extraDirs[] = dirname($pm2Bin);
        }

        // 3. Fallback folder umum Node/PM2
        if ($isWin) {
            $programFiles = getenv('ProgramFiles') ?: 'C:\\Program Files';
            $programFilesX86 = getenv('ProgramFiles(x86)') ?: 'C:\\Program Files (x86)';
            $appData = getenv('APPDATA');
            $localAppData = getenv('LOCALAPPDATA');

            $commonWinDirs = [
                'D:\\nodejs',
                'C:\\nodejs',
                $programFiles . '\\nodejs',
                $programFilesX86 . '\\nodejs',
            ];
            if ($appData) {
                $commonWinDirs[] = $appData . '\\npm';
            }
            if ($localAppData) {
                $commonWinDirs[] = $localAppData . '\\Programs\\node';
            }

            foreach ($commonWinDirs as $dir) {
                if (is_dir($dir)) {
                    $extraDirs[] = $dir;
                }
            }
        } else {
            $commonLinuxDirs = ['/usr/local/bin', '/usr/bin', '/bin'];
            foreach ($commonLinuxDirs as $dir) {
                if (is_dir($dir)) {
                    $extraDirs[] = $dir;
                }
            }
        }

        // Gabungkan extraDirs dan pathDirs (hindari duplikat)
        $mergedDirs = [];
        foreach (array_merge($extraDirs, $pathDirs) as $dir) {
            $normalized = rtrim($dir, '/\\');
            if ($normalized !== '' && !in_array($normalized, $mergedDirs, true)) {
                $mergedDirs[] = $normalized;
            }
        }

        $env = [
            'PATH' => implode($sep, $mergedDirs),
        ];

        $pm2Home = config('services.wa_bot.pm2_home');
        if ($pm2Home) {
            $env['PM2_HOME'] = $pm2Home;
        }

        return $env;
    }

    /**
     * Cek apakah sebuah perintah tersedia di sistem (path file atau where/which).
     */
    protected function commandExists(string $bin): bool
    {
        $absPath = $this->makeAbsolutePath($bin);
        if (is_file($absPath)) {
            return true;
        }

        if ($this->isWindows()) {
            if (!preg_match('/\.(cmd|bat|exe)$/i', $absPath) && is_file($absPath . '.cmd')) {
                return true;
            }
        }

        $looksLikePath = str_contains($bin, '\\')
            || str_contains($bin, '/')
            || preg_match('/\.(cmd|bat|exe|ps1)$/i', $bin);

        if ($looksLikePath) {
            return false;
        }

        try {
            $checker = $this->isWindows() ? 'where' : 'which';
            $process = new Process([$checker, $bin]);
            $process->setEnv($this->getEnvironmentVariables());
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
    protected function runPm2(string|array $command): array
    {
        $bin = $this->resolvePm2Binary();
        $botDirConfig = config('services.wa_bot.bot_dir', 'bot');
        $botDir = $this->makeAbsolutePath($botDirConfig ?: 'bot');

        if (is_array($command)) {
            $cmdArray = array_merge([$bin], $command);
        } else {
            $parts = array_values(array_filter(explode(' ', $command), fn($s) => $s !== ''));
            $cmdArray = array_merge([$bin], $parts);
        }

        $process = new Process($cmdArray);
        $process->setWorkingDirectory($botDir);
        $process->setTimeout(30);
        $process->setEnv($this->getEnvironmentVariables());

        $process->run();

        return [
            'success' => $process->isSuccessful(),
            'output' => $process->getOutput(),
            'error' => $process->getErrorOutput(),
            'exitCode' => $process->getExitCode(),
            'command' => implode(' ', $cmdArray),
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
    /**
     * Nyalakan atau restart proses bot via PM2 (setara npm start)
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

        // Cek apakah HTTP API bot benar-benar merespon
        $isHttpOnline = false;
        try {
            $resp = Http::timeout(2)->get("{$this->baseUrl}/api/status");
            if ($resp->successful()) {
                $isHttpOnline = true;
            }
        } catch (\Throwable $e) {
            $isHttpOnline = false;
        }

        if ($info['registered'] && $info['status'] === 'online' && $isHttpOnline) {
            return ['success' => true, 'message' => 'Bot WhatsApp sudah berjalan (online).'];
        }

        $command = ($info['registered'] && $info['status'] === 'online')
            ? "restart {$appName}"
            : ($info['registered'] ? "start {$appName}" : "start index.js --name {$appName}");

        $result = $this->runPm2($command);

        return [
            'success' => $result['success'],
            'message' => $result['success']
                ? 'Proses bot WhatsApp berhasil dihidupkan/direset (PM2).'
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
     * Kirim pesan WhatsApp dengan lampiran media/file (Gambar/PDF/Dokumen)
     */
    public function sendMediaMessage(string $phone, string $message, string $filePathOrUrl, ?string $fileName = null): array
    {
        try {
            $response = Http::timeout(20)->post("{$this->baseUrl}/api/send", [
                'phone' => $phone,
                'message' => $message,
                'filePath' => $filePathOrUrl,
                'fileName' => $fileName,
            ]);

            return $response->json();
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan media via Bot WA: ' . $e->getMessage(),
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
            // Hapus sesi lokal secara manual jika service bot offline
            $botDirConfig = config('services.wa_bot.bot_dir', 'bot');
            $sessionDir = $this->makeAbsolutePath($botDirConfig . '/sijurnalsesion');

            if (is_dir($sessionDir)) {
                try {
                    \Illuminate\Support\Facades\File::deleteDirectory($sessionDir);
                } catch (\Throwable $t) {
                    // Ignore failure
                }
            }

            return [
                'success' => true,
                'message' => 'Bot offline saat logout, tetapi sesi lokal berhasil dibersihkan.',
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
