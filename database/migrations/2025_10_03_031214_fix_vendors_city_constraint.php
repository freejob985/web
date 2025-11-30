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
            // Make city and governorate nullable since we now use city_id and governorate_id
            $table->string('city')->nullable()->change();
            $table->string('governorate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // Revert city and governorate back to required
            $table->string('city')->nullable(false)->change();
            $table->string('governorate')->nullable(false)->change();
        });
    }
};
