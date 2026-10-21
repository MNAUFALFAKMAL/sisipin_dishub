<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel surat_masuks — surat yang diterima dari luar (FR-2.1)
     */
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100);
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('pengirim', 150);
            $table->string('perihal', 255);
            $table->text('isi_ringkas')->nullable();
            $table->string('file_surat')->nullable(); // path file scan
            $table->foreignId('bidang_tujuan_id')->nullable()->constrained('bidangs')->nullOnDelete();
            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status_disposisi', ['belum', 'diproses', 'selesai'])->default('belum');
            $table->text('catatan_disposisi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
