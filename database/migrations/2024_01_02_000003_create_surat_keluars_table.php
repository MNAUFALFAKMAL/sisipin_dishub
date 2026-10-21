<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel surat_keluars — surat yang dikirimkan ke luar (FR-2.2)
     */
    public function up(): void
    {
        Schema::create('surat_keluars', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat', 100)->nullable(); // bisa otomatis atau manual
            $table->date('tanggal_surat');
            $table->string('tujuan', 150);
            $table->string('perihal', 255);
            $table->text('isi_ringkas')->nullable();
            $table->string('file_surat')->nullable();
            $table->foreignId('bidang_id')->nullable()->constrained('bidangs')->nullOnDelete();
            $table->foreignId('dibuat_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluars');
    }
};
