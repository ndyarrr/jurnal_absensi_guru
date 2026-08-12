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
        Schema::table('detail_ketidakhadiran', function (Blueprint $table) {
            $table->enum('kategori', ['sakit', 'izin_ortu', 'dispensasi', 'alpa'])->default('sakit')->after('status');
            $table->string('bukti_surat')->nullable()->after('kategori');
            $table->text('catatan')->nullable()->after('bukti_surat');
            $table->integer('id_guru_piket')->nullable()->after('catatan');
            $table->timestamp('waktu_input')->nullable()->after('id_guru_piket');

            $table->foreign('id_guru_piket')->references('id_guru')->on('guru')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_ketidakhadiran', function (Blueprint $table) {
            $table->dropForeign(['id_guru_piket']);
            $table->dropColumn(['kategori', 'bukti_surat', 'catatan', 'id_guru_piket', 'waktu_input']);
        });
    }
};
