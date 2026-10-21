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
        Schema::create('tata_naskahs', function (Blueprint $table) {
            $table->id();
            $table->string('kategori');
            $table->string('sub_kategori')->nullable();
            $table->string('nama_naskah');
            $table->text('definisi')->nullable();
            $table->string('file_docx')->nullable();
            $table->string('file_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tata_naskahs');
    }
};
