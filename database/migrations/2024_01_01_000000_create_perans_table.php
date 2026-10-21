<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel peran (roles) untuk manajemen hak akses pengguna
     */
    public function up(): void
    {
        Schema::create('perans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->unique(); // Super Admin, Tata Usaha, Pegawai, Kepala Dinas, Kepala Bidang, Staff
            $table->string('slug', 50)->unique(); // super_admin, tata_usaha, pegawai, kepala_dinas, kepala_bidang, staff
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perans');
    }
};