<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel bidang (unit kerja / bagian dalam dinas)
     */
    public function up(): void
    {
        Schema::create('bidangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bidang', 100);
            $table->string('kode_bidang', 10)->nullable()->unique();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bidangs');
    }
};
