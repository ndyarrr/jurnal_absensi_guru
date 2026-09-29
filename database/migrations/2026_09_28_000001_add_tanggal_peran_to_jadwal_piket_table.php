<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('jadwal_piket')) {
            return;
        }

        Schema::table('jadwal_piket', function (Blueprint $table) {
            if (!Schema::hasColumn('jadwal_piket', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('hari');
                $table->index('tanggal');
            }
            if (!Schema::hasColumn('jadwal_piket', 'peran')) {
                $table->string('peran', 50)->nullable()->after('id_guru');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('jadwal_piket')) {
            return;
        }

        Schema::table('jadwal_piket', function (Blueprint $table) {
            if (Schema::hasColumn('jadwal_piket', 'tanggal')) {
                $table->dropIndex(['tanggal']);
                $table->dropColumn('tanggal');
            }
            if (Schema::hasColumn('jadwal_piket', 'peran')) {
                $table->dropColumn('peran');
            }
        });
    }
};