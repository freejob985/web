<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->enum('status', ['active', 'unsubscribed'])->default('active');
            $table->timestamp('subscribed_at')->useCurrent();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('content');
            $table->text('html_content')->nullable();
            $table->enum('status', ['draft', 'sending', 'sent', 'failed'])->default('draft');
            $table->integer('total_recipients')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('failed_count')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('newsletter_campaigns')->onDelete('cascade');
            $table->foreignId('newsletter_id')->constrained('newsletters')->onDelete('cascade');
            $table->enum('status', ['sent', 'failed', 'bounced'])->default('sent');
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_logs');
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletters');
    }
};