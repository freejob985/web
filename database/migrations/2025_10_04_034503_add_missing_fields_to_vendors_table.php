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
            // إضافة الحقول المفقودة لتسجيل المورد (فقط الحقول غير الموجودة)
            if (!Schema::hasColumn('vendors', 'business_name')) {
                $table->string('business_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('vendors', 'business_type')) {
                $table->string('business_type')->nullable()->after('business_name');
            }
            if (!Schema::hasColumn('vendors', 'tax_number')) {
                $table->string('tax_number')->nullable()->after('commercial_record');
            }
            if (!Schema::hasColumn('vendors', 'bank_account')) {
                $table->string('bank_account')->nullable()->after('tax_number');
            }
            if (!Schema::hasColumn('vendors', 'bank_name')) {
                $table->string('bank_name')->nullable()->after('bank_account');
            }
            if (!Schema::hasColumn('vendors', 'governorate_id')) {
                $table->unsignedBigInteger('governorate_id')->nullable()->after('governorate');
            }
            if (!Schema::hasColumn('vendors', 'city_id')) {
                $table->unsignedBigInteger('city_id')->nullable()->after('city');
            }
            if (!Schema::hasColumn('vendors', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('is_active');
            }
            if (!Schema::hasColumn('vendors', 'is_fresh')) {
                $table->boolean('is_fresh')->default(false)->after('is_featured');
            }
            if (!Schema::hasColumn('vendors', 'category')) {
                $table->string('category')->nullable()->after('is_fresh');
            }
            if (!Schema::hasColumn('vendors', 'description')) {
                $table->text('description')->nullable()->after('business_description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendors', function (Blueprint $table) {
            // حذف الحقول المضافة
            $table->dropColumn([
                'business_name',
                'business_type',
                'tax_number',
                'bank_account',
                'bank_name',
                'governorate_id',
                'city_id',
                'is_featured',
                'is_fresh',
                'category',
                'description'
            ]);
        });
    }
};
