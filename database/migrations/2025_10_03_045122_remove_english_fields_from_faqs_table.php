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
        Schema::table('faqs', function (Blueprint $table) {
            if (Schema::hasColumn('faqs', 'question_en')) {
                $table->dropColumn('question_en');
            }
            if (Schema::hasColumn('faqs', 'answer_en')) {
                $table->dropColumn('answer_en');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faqs', function (Blueprint $table) {
            $table->string('question_en')->nullable();
            $table->text('answer_en')->nullable();
        });
    }
};