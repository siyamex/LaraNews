<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('subscribers_count')->default(0);
            $table->timestamps();
        });

        Schema::create('newsletter_subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->string('name')->nullable();
            $table->string('locale', 10)->default('dv');
            $table->enum('status', ['subscribed', 'unsubscribed', 'bounced', 'complained'])->default('subscribed')->index();
            $table->string('token', 64)->unique()->nullable();
            $table->json('preferences')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });

        Schema::create('newsletter_list_subscriber', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('list_id')->index();
            $table->unsignedBigInteger('subscriber_id')->index();
            $table->timestamps();

            $table->unique(['list_id', 'subscriber_id']);
            $table->foreign('list_id')->references('id')->on('newsletter_lists')->onDelete('cascade');
            $table->foreign('subscriber_id')->references('id')->on('newsletter_subscribers')->onDelete('cascade');
        });

        Schema::create('newsletter_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->longText('content');
            $table->unsignedBigInteger('list_id')->nullable()->index();
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent', 'cancelled'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedBigInteger('recipients_count')->default(0);
            $table->unsignedBigInteger('opens_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->unsignedBigInteger('bounces_count')->default(0);
            $table->timestamps();

            $table->foreign('list_id')->references('id')->on('newsletter_lists')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_campaigns');
        Schema::dropIfExists('newsletter_list_subscriber');
        Schema::dropIfExists('newsletter_subscribers');
        Schema::dropIfExists('newsletter_lists');
    }
};
