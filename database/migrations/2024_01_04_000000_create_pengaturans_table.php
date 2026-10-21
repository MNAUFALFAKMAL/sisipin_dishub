<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pengaturan aplikasi (key-value store untuk konfigurasi dinamis)
     */
    public function up(): void
    {
        Schema::create('pengaturans', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 100)->unique(); // Kunci pengaturan (e.g., 'app_name', 'items_per_page')
            $table->text('nilai')->nullable(); // Nilai pengaturan (JSON, string, number, dll)
            $table->string('tipe', 20)->default('string'); // string, integer, boolean, json, array
            $table->string('kelompok', 50)->nullable()->index(); // Kelompok pengaturan (e.g., 'general', 'surat', 'email')
            $table->string('label', 150)->nullable(); // Label tampilan untuk UI
            $table->text('deskripsi')->nullable(); // Deskripsi pengaturan
            $table->boolean('is_public')->default(false); // Bisa diakses publik (untuk frontend)
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['kelompok', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaturans');
    }
};