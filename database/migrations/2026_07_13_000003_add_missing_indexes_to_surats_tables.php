<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->index('no_agenda');
            $table->index('jenis_surat');
            $table->index('tanggal_terima');
            $table->index('tanggal_surat');
            $table->index('status_disposisi');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->index('no_agenda');
            $table->index('jenis_surat');
            $table->index('tanggal_terima');
            $table->index('tanggal_surat');
        });

        Schema::table('surat_masuk_bidang', function (Blueprint $table) {
            $table->index('surat_masuk_id');
            $table->index('bidang_id');
        });

        Schema::table('surat_keluar_bidang', function (Blueprint $table) {
            $table->index('surat_keluar_id');
            $table->index('bidang_id');
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropIndex(['no_agenda']);
            $table->dropIndex(['jenis_surat']);
            $table->dropIndex(['tanggal_terima']);
            $table->dropIndex(['tanggal_surat']);
            $table->dropIndex(['status_disposisi']);
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropIndex(['no_agenda']);
            $table->dropIndex(['jenis_surat']);
            $table->dropIndex(['tanggal_terima']);
            $table->dropIndex(['tanggal_surat']);
        });

        Schema::table('surat_masuk_bidang', function (Blueprint $table) {
            $table->dropIndex(['surat_masuk_id']);
            $table->dropIndex(['bidang_id']);
        });

        Schema::table('surat_keluar_bidang', function (Blueprint $table) {
            $table->dropIndex(['surat_keluar_id']);
            $table->dropIndex(['bidang_id']);
        });
    }
};
