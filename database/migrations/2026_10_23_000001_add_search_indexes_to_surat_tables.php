<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add indexes for search optimization on surat_masuks and surat_keluars
     */
    public function up(): void
    {
        // Indexes for Surat Masuk search
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->index(['no_agenda', 'tahun'], 'idx_surat_masuk_agenda_tahun');
            $table->index(['no_surat', 'tahun'], 'idx_surat_masuk_nomor_tahun');
            $table->index('perihal', 'idx_surat_masuk_perihal');
            $table->index('pengirim', 'idx_surat_masuk_pengirim');
            $table->index(['tahun', 'status_disposisi'], 'idx_surat_masuk_tahun_status');
            $table->index(['tahun', 'bidang_tujuan_id'], 'idx_surat_masuk_tahun_bidang');
            $table->index(['tahun', 'klasifikasi_id'], 'idx_surat_masuk_tahun_klasifikasi');
        });

        // Indexes for Surat Keluar search
        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->index(['no_agenda', 'tahun'], 'idx_surat_keluar_agenda_tahun');
            $table->index(['no_surat', 'tahun'], 'idx_surat_keluar_nomor_tahun');
            $table->index('perihal', 'idx_surat_keluar_perihal');
            $table->index('tujuan', 'idx_surat_keluar_tujuan');
            $table->index(['tahun', 'status'], 'idx_surat_keluar_tahun_status');
            $table->index(['tahun', 'jenis_surat'], 'idx_surat_keluar_tahun_jenis');
            $table->index(['tahun', 'bidang_id'], 'idx_surat_keluar_tahun_bidang');
            $table->index(['tahun', 'klasifikasi_id'], 'idx_surat_keluar_tahun_klasifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropIndex('idx_surat_masuk_agenda_tahun');
            $table->dropIndex('idx_surat_masuk_nomor_tahun');
            $table->dropIndex('idx_surat_masuk_perihal');
            $table->dropIndex('idx_surat_masuk_pengirim');
            $table->dropIndex('idx_surat_masuk_tahun_status');
            $table->dropIndex('idx_surat_masuk_tahun_bidang');
            $table->dropIndex('idx_surat_masuk_tahun_klasifikasi');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropIndex('idx_surat_keluar_agenda_tahun');
            $table->dropIndex('idx_surat_keluar_nomor_tahun');
            $table->dropIndex('idx_surat_keluar_perihal');
            $table->dropIndex('idx_surat_keluar_tujuan');
            $table->dropIndex('idx_surat_keluar_tahun_status');
            $table->dropIndex('idx_surat_keluar_tahun_jenis');
            $table->dropIndex('idx_surat_keluar_tahun_bidang');
            $table->dropIndex('idx_surat_keluar_tahun_klasifikasi');
        });
    }
};