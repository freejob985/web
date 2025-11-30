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
        // إضافة حقول Firebase tokens للمستخدمين
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'fcm_token')) {
                $table->string('fcm_token')->nullable()->after('remember_token');
            }
        });

        // إضافة جدول للسائقين إذا لم يكن موجوداً
        if (!Schema::hasTable('drivers')) {
            Schema::create('drivers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('phone')->unique();
                $table->string('email')->unique()->nullable();
                $table->string('license_number')->unique();
                $table->string('vehicle_type')->nullable();
                $table->string('vehicle_number')->nullable();
                $table->enum('status', ['active', 'inactive', 'busy'])->default('active');
                $table->string('fcm_token')->nullable(); // Firebase token
                $table->timestamps();
            });
        } else {
            // إذا كان الجدول موجود، أضف fcm_token فقط
            Schema::table('drivers', function (Blueprint $table) {
                if (!Schema::hasColumn('drivers', 'fcm_token')) {
                    $table->string('fcm_token')->nullable();
                }
            });
        }

        // ربط السائق بالطلب
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'driver_id')) {
                $table->foreignId('driver_id')->nullable()->after('vendor_id')->constrained('drivers')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'fcm_token')) {
                $table->dropColumn('fcm_token');
            }
        });

        if (Schema::hasTable('drivers')) {
            Schema::table('drivers', function (Blueprint $table) {
                if (Schema::hasColumn('drivers', 'fcm_token')) {
                    $table->dropColumn('fcm_token');
                }
            });
        }

        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'driver_id')) {
                $table->dropForeign(['driver_id']);
                $table->dropColumn('driver_id');
            }
        });
    }
};
