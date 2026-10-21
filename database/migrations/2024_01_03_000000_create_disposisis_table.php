<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel disposisi untuk melacak alur disposisi surat masuk
     */
    public function up(): void
    {
        Schema::create('disposisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade');
            $table->foreignId('dari_user_id')->nullable()->constrained('users')->nullOnDelete(); // User yang mendelegasikan
            $table->foreignId('kepada_user_id')->nullable()->constrained('users')->nullOnDelete(); // User yang menerima disposisi
            $table->foreignId('kepada_bidang_id')->nullable()->constrained('bidangs')->nullOnDelete(); // Bidang tujuan (opsional)
            $table->text('instruksi')->nullable(); // Instruksi disposisi
            $table->enum('status', ['baru', 'dibaca', 'diproses', 'selesai', 'ditolak'])->default('baru');
            $table->text('catatan')->nullable(); // Catatan dari penerima
            $table->timestamp('tanggal_disposisi')->useCurrent(); // Waktu disposisi dibuat
            $table->timestamp('tanggal_baca')->nullable(); // Waktu dibaca
            $table->timestamp('tanggal_selesai')->nullable(); // Waktu diselesaikan
            $table->timestamps();

            // Indexes
            $table->index('surat_masuk_id');
            $table->index('dari_user_id');
            $table->index('kepada_user_id');
            $table->index('kepada_bidang_id');
            $table->index('status');
            $table->index('tanggal_disposisi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disposisis');
    }
};