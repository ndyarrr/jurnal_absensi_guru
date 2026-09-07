<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('izin_guru', function (Blueprint $table) {
            if (!Schema::hasColumn('izin_guru', 'approval_token')) {
                $table->string('approval_token', 64)->nullable()->unique()->after('status_approval');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izin_guru', function (Blueprint $table) {
            if (Schema::hasColumn('izin_guru', 'approval_token')) {
                $table->dropColumn('approval_token');
            }
        });
    }
};
