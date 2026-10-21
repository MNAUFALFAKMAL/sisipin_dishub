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
            $table->string('jenis_surat', 50)->default('Surat Masuk')->after('no_agenda');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->string('jenis_surat', 50)->default('Surat Keluar')->after('no_agenda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn('jenis_surat');
        });

        Schema::table('surat_keluars', function (Blueprint $table) {
            $table->dropColumn('jenis_surat');
        });
    }
};
