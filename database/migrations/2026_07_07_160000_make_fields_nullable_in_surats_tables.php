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
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->string('no_surat', 100)->nullable()->change();
            $table->date('tanggal_surat')->nullable()->change();
            $table->date('tanggal_terima')->nullable()->change();
            $table->string('pengirim', 150)->nullable()->change();
            $table->string('perihal', 255)->nullable()->change();
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->date('tanggal_surat')->nullable()->change();
            $table->string('tujuan', 150)->nullable()->change();
            $table->string('perihal', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->string('no_surat', 100)->nullable(false)->change();
            $table->date('tanggal_surat')->nullable(false)->change();
            $table->date('tanggal_terima')->nullable(false)->change();
            $table->string('pengirim', 150)->nullable(false)->change();
            $table->string('perihal', 255)->nullable(false)->change();
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->date('tanggal_surat')->nullable(false)->change();
            $table->string('tujuan', 150)->nullable(false)->change();
            $table->string('perihal', 255)->nullable(false)->change();
        });
    }
};
