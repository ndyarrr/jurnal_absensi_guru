<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Guru;

class DeduplicateGuruCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'guru:deduplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghilangkan data guru yang ganda (duplicate) dan memperbarui referensi relasinya.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pembersihan data guru ganda...');
        $result = self::runDeduplication();

        if ($result['deleted_count'] > 0) {
            $this->info("Berhasil! {$result['merged_count']} kelompok guru ganda berhasil digabungkan dan {$result['deleted_count']} data guru ganda dihapus.");
        } else {
            $this->info('Tidak ditemukan data guru ganda. Data guru sudah bersih.');
        }

        return Command::SUCCESS;
    }

    /**
     * Public static helper logic for deduplicating teachers so it can be called from command or controller.
     */
    public static function runDeduplication(): array
    {
        $gurus = Guru::withTrashed()->orderBy('id_guru', 'asc')->get();

        $grouped = [];
        foreach ($gurus as $g) {
            $key = strtolower(trim($g->nama_guru));
            $grouped[$key][] = $g;
        }

        $mergedCount = 0;
        $deletedCount = 0;

        DB::transaction(function () use ($grouped, &$mergedCount, &$deletedCount) {
            foreach ($grouped as $key => $list) {
                if (count($list) <= 1) {
                    continue;
                }

                $primary = $list[0];
                $primaryId = $primary->id_guru;

                $duplicateIds = [];
                for ($i = 1; $i < count($list); $i++) {
                    $duplicateIds[] = $list[$i]->id_guru;
                }

                self::relinkForeignKeys($primaryId, $duplicateIds);

                DB::table('guru')->whereIn('id_guru', $duplicateIds)->delete();

                $mergedCount++;
                $deletedCount += count($duplicateIds);
            }
        });

        return [
            'merged_count'  => $mergedCount,
            'deleted_count' => $deletedCount,
        ];
    }

    /**
     * Re-link foreign key references from duplicate IDs to primary ID
     */
    public static function relinkForeignKeys(int $primaryId, array $duplicateIds): void
    {
        if (empty($duplicateIds)) {
            return;
        }

        // 1. guru_mapel (id_guru, id_mapel)
        foreach ($duplicateIds as $dupId) {
            $mapels = DB::table('guru_mapel')->where('id_guru', $dupId)->get();
            foreach ($mapels as $m) {
                $exists = DB::table('guru_mapel')
                    ->where('id_guru', $primaryId)
                    ->where('id_mapel', $m->id_mapel)
                    ->exists();
                if (!$exists) {
                    DB::table('guru_mapel')
                        ->where('id_guru', $dupId)
                        ->where('id_mapel', $m->id_mapel)
                        ->update(['id_guru' => $primaryId]);
                } else {
                    DB::table('guru_mapel')
                        ->where('id_guru', $dupId)
                        ->where('id_mapel', $m->id_mapel)
                        ->delete();
                }
            }
        }

        // 2. users (id_guru)
        $primaryUserExists = DB::table('users')->where('id_guru', $primaryId)->exists();
        if ($primaryUserExists) {
            DB::table('users')->whereIn('id_guru', $duplicateIds)->delete();
        } else {
            $dupUsers = DB::table('users')->whereIn('id_guru', $duplicateIds)->get();
            if ($dupUsers->count() > 0) {
                $firstDupUser = $dupUsers->first();
                DB::table('users')->where('id', $firstDupUser->id)->update(['id_guru' => $primaryId]);
                $otherDupUserIds = $dupUsers->pluck('id')->filter(fn($id) => $id != $firstDupUser->id)->toArray();
                if (!empty($otherDupUserIds)) {
                    DB::table('users')->whereIn('id', $otherDupUserIds)->delete();
                }
            }
        }

        // 3. kelas (id_guru_wali)
        if (Schema::hasTable('kelas')) {
            DB::table('kelas')->whereIn('id_guru_wali', $duplicateIds)->update(['id_guru_wali' => $primaryId]);
        }

        // 4. jadwal_pelajaran (id_guru)
        if (Schema::hasTable('jadwal_pelajaran')) {
            DB::table('jadwal_pelajaran')->whereIn('id_guru', $duplicateIds)->update(['id_guru' => $primaryId]);
        }

        // 5. jurnal_mengajar (id_guru_pengganti)
        if (Schema::hasTable('jurnal_mengajar')) {
            DB::table('jurnal_mengajar')->whereIn('id_guru_pengganti', $duplicateIds)->update(['id_guru_pengganti' => $primaryId]);
        }

        // 6. detail_ketidakhadiran (id_guru_piket)
        if (Schema::hasTable('detail_ketidakhadiran')) {
            DB::table('detail_ketidakhadiran')->whereIn('id_guru_piket', $duplicateIds)->update(['id_guru_piket' => $primaryId]);
        }

        // 7. izin_guru (id_guru)
        if (Schema::hasTable('izin_guru')) {
            DB::table('izin_guru')->whereIn('id_guru', $duplicateIds)->update(['id_guru' => $primaryId]);
        }

        // 8. jadwal_piket (id_guru)
        if (Schema::hasTable('jadwal_piket')) {
            DB::table('jadwal_piket')->whereIn('id_guru', $duplicateIds)->update(['id_guru' => $primaryId]);
        }

        // 9. permohonan_izin (id_guru)
        if (Schema::hasTable('permohonan_izin')) {
            DB::table('permohonan_izin')->whereIn('id_guru', $duplicateIds)->update(['id_guru' => $primaryId]);
        }

        // 10. surat_dispensasi (id_guru)
        if (Schema::hasTable('surat_dispensasi')) {
            DB::table('surat_dispensasi')->whereIn('id_guru', $duplicateIds)->update(['id_guru' => $primaryId]);
        }
    }
}
