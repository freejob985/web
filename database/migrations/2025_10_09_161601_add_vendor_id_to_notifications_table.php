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
        Schema::table('notifications', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->constrained('vendors')->onDelete('cascade')->after('id');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('vendor_id');
            $table->index(['vendor_id', 'is_read']);
            $table->index(['user_id', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['user_id']);
            $table->dropIndex(['vendor_id', 'is_read']);
            $table->dropIndex(['user_id', 'is_read']);
            $table->dropColumn(['vendor_id', 'user_id']);
        });
    }
};
