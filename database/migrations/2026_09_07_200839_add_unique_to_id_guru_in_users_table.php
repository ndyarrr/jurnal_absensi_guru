<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Enforce strict 1-to-1 relationship: 1 Profil Guru = 1 Akun User.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unique('id_guru', 'users_id_guru_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_id_guru_unique');
        });
    }
};
