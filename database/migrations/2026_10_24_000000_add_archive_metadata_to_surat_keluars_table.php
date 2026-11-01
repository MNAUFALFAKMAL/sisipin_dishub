<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom metadata arsip surat keluar yang dipakai controller/form
     * tetapi belum pernah dibuat oleh migration sebelumnya.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('surat_keluars', 'penandatangan')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('penandatangan', 150)->nullable()->after('bidang_id');
            });
        }

        if (! Schema::hasColumn('surat_keluars', 'status')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('status', 20)->default('draft')->index()->after('penandatangan');
            });
        }

        if (! Schema::hasColumn('surat_keluars', 'indeks_berkas')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('indeks_berkas', 100)->nullable()->after('status');
            });
        }

        if (! Schema::hasColumn('surat_keluars', 'tingkat_keamanan')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('tingkat_keamanan', 50)->nullable()->after('indeks_berkas');
            });
        }

        if (! Schema::hasColumn('surat_keluars', 'masa_simpan')) {
            Schema::table('surat_keluars', function (Blueprint $table) {
                $table->string('masa_simpan', 100)->nullable()->after('tingkat_keamanan');
            });
        }
    }

    public function down(): void
    {
        foreach (['masa_simpan', 'tingkat_keamanan', 'indeks_berkas', 'status', 'penandatangan'] as $column) {
            if (Schema::hasColumn('surat_keluars', $column)) {
                Schema::table('surat_keluars', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
