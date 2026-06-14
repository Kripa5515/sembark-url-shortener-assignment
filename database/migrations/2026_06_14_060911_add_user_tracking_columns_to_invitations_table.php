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
        Schema::table('invitations', function (Blueprint $table) {
            $table->foreignId('created_by')
                  ->after('role')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            $table->foreignId('created_for')
                  ->after('created_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invitations', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['created_for']);
            $table->dropColumn(['created_by', 'created_for']);
        });
    }
};
