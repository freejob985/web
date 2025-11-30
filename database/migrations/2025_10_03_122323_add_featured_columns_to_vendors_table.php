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
        Schema::table('vendors', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('is_active');
            $table->boolean('is_fresh')->default(false)->after('is_featured');
            $table->string('category')->nullable()->after('is_fresh');
            $table->string('name_ar')->nullable()->after('name');
            $table->text('description')->nullable()->after('name_ar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'is_fresh', 'category', 'name_ar', 'description']);
        });
    }
};
