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
        Schema::table('klasifikasi_arsips', function (Blueprint $table) {
            $table->string('keamanan')->nullable()->after('nasib_akhir');
            $table->string('hak_akses')->nullable()->after('keamanan');
            $table->text('dasar_pertimbangan')->nullable()->after('hak_akses');
            $table->string('unit_pengolah')->nullable()->after('dasar_pertimbangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('klasifikasi_arsips', function (Blueprint $table) {
            $table->dropColumn([
                'keamanan',
                'hak_akses',
                'dasar_pertimbangan',
                'unit_pengolah'
            ]);
        });
    }
};
