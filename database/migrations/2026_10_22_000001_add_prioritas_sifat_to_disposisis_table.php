<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom prioritas dan sifat ke tabel disposisis
     */
    public function up(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->enum('prioritas', ['biasa', 'segera', 'mendesak'])->default('biasa')->after('instruksi');
            $table->enum('sifat', ['biasa', 'rahasia', 'terbatas'])->default('biasa')->after('prioritas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('disposisis', function (Blueprint $table) {
            $table->dropColumn(['prioritas', 'sifat']);
        });
    }
};