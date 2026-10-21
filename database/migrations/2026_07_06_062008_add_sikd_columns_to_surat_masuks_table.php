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
            $table->string('no_agenda', 50)->nullable()->after('id');
            $table->foreignId('klasifikasi_id')->nullable()->after('no_agenda')->constrained('klasifikasi_arsips')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropForeign(['klasifikasi_id']);
            $table->dropColumn(['no_agenda', 'klasifikasi_id']);
        });
    }
};
