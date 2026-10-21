<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Migrate role enum to peran_id foreign key
     */
    public function up(): void
    {
        // Map old role values to peran slugs
        $roleMap = [
            'super_admin' => 'super_admin',
            'kepala_dinas' => 'kepala_dinas',
            'kepala_bidang' => 'kepala_bidang',
            'staff' => 'staff',
            'tata_usaha' => 'tata_usaha',
        ];

        foreach ($roleMap as $oldRole => $peranSlug) {
            $peranId = DB::table('perans')->where('slug', $peranSlug)->value('id');
            if ($peranId) {
                DB::table('users')
                    ->where('role', $oldRole)
                    ->update(['peran_id' => $peranId]);
            }
        }

        // Drop the old role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'super_admin',
                'kepala_dinas',
                'kepala_bidang',
                'staff',
                'tata_usaha',
            ])->default('staff')->after('nip');
        });

        // Restore role from peran_id
        $peranMap = [
            'super_admin' => 'super_admin',
            'kepala_dinas' => 'kepala_dinas',
            'kepala_bidang' => 'kepala_bidang',
            'staff' => 'staff',
            'tata_usaha' => 'tata_usaha',
        ];

        foreach ($peranMap as $peranSlug => $role) {
            $peranId = DB::table('perans')->where('slug', $peranSlug)->value('id');
            if ($peranId) {
                DB::table('users')
                    ->where('peran_id', $peranId)
                    ->update(['role' => $role]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['peran_id']);
            $table->dropColumn('peran_id');
        });
    }
};