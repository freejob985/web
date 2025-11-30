<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->json('preferences')->nullable();
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sms_notifications')->default(false);
            $table->boolean('order_updates')->default(true);
            $table->boolean('promotions')->default(true);
            $table->boolean('newsletter')->default(false);
            $table->decimal('total_spent', 12, 3)->default(0);
            $table->integer('orders_count')->default(0);
            $table->timestamp('last_order_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
