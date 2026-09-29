<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_ketidakhadiran', function (Blueprint $table) {
            $table->enum('jenis_dispen', ['masuk', 'keluar'])
                ->nullable()
                ->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('detail_ketidakhadiran', function (Blueprint $table) {
            $table->dropColumn('jenis_dispen');
        });
    }
};