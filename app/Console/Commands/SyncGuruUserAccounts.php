<?php

namespace App\Console\Commands;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SyncGuruUserAccounts extends Command
{
    protected $signature   = 'guru:sync-accounts {--reset-password : Reset semua password guru ke NIP}';
    protected $description = 'Sinkronisasi akun user untuk semua guru: buat akun yang belum ada, username = NIP, password default = NIP';

    public function handle(): int
    {
        $gurus = Guru::withTrashed(false)->get(); // hanya guru aktif (tidak soft-deleted)

        $created  = 0;
        $updated  = 0;
        $skipped  = 0;
        $noNip    = 0;

        $resetPassword = $this->option('reset-password');

        $this->info("Total guru aktif: {$gurus->count()}");
        $this->newLine();

        foreach ($gurus as $guru) {
            // Guru tanpa NIP tidak bisa dibuat username unik
            if (empty($guru->nip)) {
                $this->warn("  [SKIP] {$guru->nama_guru} — tidak memiliki NIP");
                $noNip++;
                continue;
            }

            // Cari user yang terhubung ke guru ini
            $user = User::where('id_guru', $guru->id_guru)->first();

            if (! $user) {
                // Cek apakah username NIP sudah dipakai user lain
                $existingByUsername = User::where('username', $guru->nip)->first();
                if ($existingByUsername) {
                    // Link user tersebut ke guru ini
                    $existingByUsername->update([
                        'name'    => $guru->nama_guru,
                        'id_guru' => $guru->id_guru,
                    ]);
                    if ($resetPassword) {
                        $existingByUsername->update(['password' => Hash::make($guru->nip)]);
                    }
                    $this->line("  [LINKED] {$guru->nama_guru} → user '{$existingByUsername->username}' dihubungkan");
                    $updated++;
                    continue;
                }

                // Buat akun baru
                User::create([
                    'name'     => $guru->nama_guru,
                    'username' => $guru->nip,
                    'password' => Hash::make($guru->nip),
                    'role'     => 'guru_mengajar',
                    'id_guru'  => $guru->id_guru,
                ]);
                $this->info("  [CREATE] {$guru->nama_guru} → username: {$guru->nip}");
                $created++;
            } else {
                // Update username ke NIP jika belum sesuai
                $changes = [];

                if ($user->username !== $guru->nip) {
                    // Pastikan NIP tidak dipakai user lain
                    $conflict = User::where('username', $guru->nip)
                        ->where('id', '!=', $user->id)
                        ->first();

                    if ($conflict) {
                        $this->warn("  [CONFLICT] {$guru->nama_guru} — NIP {$guru->nip} sudah dipakai user ID {$conflict->id}");
                        $skipped++;
                        continue;
                    }

                    $changes['username'] = $guru->nip;
                }

                if ($resetPassword) {
                    $changes['password'] = Hash::make($guru->nip);
                }

                if (! empty($changes)) {
                    $user->update($changes);
                    $action = $resetPassword ? 'username + password direset' : 'username diupdate';
                    $this->line("  [UPDATE] {$guru->nama_guru} → {$action} ke NIP: {$guru->nip}");
                    $updated++;
                } else {
                    $skipped++;
                }
            }
        }

        $this->newLine();
        $this->table(
            ['Status', 'Jumlah'],
            [
                ['✅ Akun dibuat baru',    $created],
                ['🔄 Akun diupdate',       $updated],
                ['⏭  Tidak ada perubahan', $skipped],
                ['⚠️  Tanpa NIP',           $noNip],
            ]
        );

        $this->newLine();
        $this->info('Sinkronisasi selesai! Semua guru kini login menggunakan NIP sebagai username.');
        if ($resetPassword) {
            $this->warn('Password semua guru telah direset ke NIP masing-masing.');
        }

        return self::SUCCESS;
    }
}
