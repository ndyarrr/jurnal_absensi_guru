<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE izin_guru MODIFY COLUMN status_approval ENUM('pending','disetujui','disetujui_piket','disetujui_waka','disetujui_kepsek','ditolak') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE izin_guru MODIFY COLUMN status_approval ENUM('pending','disetujui_piket','disetujui_waka','disetujui_kepsek','ditolak') NOT NULL DEFAULT 'pending'");
    }
};
