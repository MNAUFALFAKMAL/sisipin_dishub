<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom tahun pada surat_masuks & surat_keluars untuk
     * scoping nomor urut (no_agenda) per tahun arsip.
     */
    public function up(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->unsignedInteger('tahun')->nullable()->index()->after('jenis_surat');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->unsignedInteger('tahun')->nullable()->index()->after('jenis_surat');
        });

        // Backfill tahun dari tanggal terima, fallback ke tanggal dibuat
        // Use database-agnostic approach for SQLite (tests) and MySQL (production)
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::table('surat_masuks')->update(['tahun' => DB::raw('YEAR(COALESCE(tanggal_terima, created_at))')]);
            DB::table('surat_keluars')->update(['tahun' => DB::raw('YEAR(COALESCE(tanggal_terima, tanggal_surat, created_at))')]);
        } elseif ($driver === 'sqlite') {
            DB::table('surat_masuks')->update(['tahun' => DB::raw("CAST(strftime('%Y', COALESCE(tanggal_terima, created_at)) AS INTEGER)")]);
            DB::table('surat_keluars')->update(['tahun' => DB::raw("CAST(strftime('%Y', COALESCE(tanggal_terima, tanggal_surat, created_at)) AS INTEGER)")]);
        }
    }

    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropIndex(['tahun']);
            $table->dropColumn('tahun');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropIndex(['tahun']);
            $table->dropColumn('tahun');
        });
    }
};
