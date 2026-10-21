<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->dropColumn('catatan_disposisi');
        });
    }

    public function down(): void
    {
        Schema::table('surat_masuks', function (Blueprint $table) {
            $table->text('catatan_disposisi')->nullable()->after('status_disposisi');
        });
    }
};
