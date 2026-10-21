<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom role, nip, dan bidang_id ke tabel users
     * Role: super_admin, kepala_dinas, kepala_bidang, staff, tata_usaha
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip', 20)->nullable()->unique()->after('name');
            $table->enum('role', [
                'super_admin',
                'kepala_dinas',
                'kepala_bidang',
                'staff',
                'tata_usaha',
            ])->default('staff')->after('nip');
            $table->foreignId('bidang_id')->nullable()->constrained('bidangs')->nullOnDelete()->after('role');
            $table->boolean('is_active')->default(true)->after('bidang_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn(['nip', 'role', 'bidang_id', 'is_active']);
        });
    }
};
