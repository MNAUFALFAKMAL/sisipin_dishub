<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Update users table: replace enum role with peran_id foreign key
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add peran_id column
            $table->foreignId('peran_id')->nullable()->constrained('perans')->nullOnDelete()->after('bidang_id');
            
            // Drop the old role enum column (will be done after data migration)
            // We keep it for now to migrate data, then drop in a separate step
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['peran_id']);
            $table->dropColumn('peran_id');
        });
    }
};