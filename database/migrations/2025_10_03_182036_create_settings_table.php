<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('string'); // string, number, boolean, json, text
            $table->string('group')->default('general'); // general, email, payment, seo, design, developer
            $table->string('label');
            $table->text('description')->nullable();
            $table->json('options')->nullable(); // For select, radio, checkbox options
            $table->boolean('is_public')->default(false); // Can be accessed via API
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};