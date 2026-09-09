<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_hp')) {
                $table->string('no_hp', 30)->nullable()->after('id_guru');
            }
        });

        // Copy no_hp from linked guru records if available
        DB::statement("
            UPDATE users u
            JOIN guru g ON u.id_guru = g.id_guru
            SET u.no_hp = g.no_hp
            WHERE g.no_hp IS NOT NULL AND g.no_hp != ''
        ");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'no_hp')) {
                $table->dropColumn('no_hp');
            }
        });
    }
};
