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
        Schema::create('surat_masuk_bidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('surat_keluar_bidang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_keluar_id')->constrained('surat_keluars')->onDelete('cascade');
            $table->foreignId('bidang_id')->constrained('bidangs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_masuk_bidang');
        Schema::dropIfExists('surat_keluar_bidang');
    }
};
