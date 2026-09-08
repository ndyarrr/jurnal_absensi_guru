<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE surat_dispensasi MODIFY COLUMN status_approval ENUM('pending','disetujui_piket','disetujui_waka','disetujui_kepsek','disetujui','ditolak') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE surat_dispensasi MODIFY COLUMN status_approval ENUM('pending','disetujui_piket','disetujui_waka','disetujui','ditolak') NOT NULL DEFAULT 'pending'");
    }
};
