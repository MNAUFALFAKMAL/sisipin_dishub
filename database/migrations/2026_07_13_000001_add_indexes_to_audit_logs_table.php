<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('activity');
            $table->index('model_type');
            $table->index('ip_address');
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['activity']);
            $table->dropIndex(['model_type']);
            $table->dropIndex(['ip_address']);
            $table->dropIndex(['model_type', 'model_id']);
        });
    }
};
