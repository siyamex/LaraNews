<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rss_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('logo')->nullable();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->boolean('auto_publish')->default(false);
            $table->boolean('ai_rewrite')->default(false);
            $table->string('default_locale', 10)->default('dv');
            $table->integer('fetch_interval')->default(60);
            $table->timestamp('last_fetched_at')->nullable();
            $table->unsignedBigInteger('items_imported')->default(0);
            $table->json('filters')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });

        Schema::create('rss_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id')->index();
            $table->unsignedBigInteger('post_id')->nullable()->index();
            $table->string('guid')->index();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'imported', 'rejected', 'duplicate'])->default('pending')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['source_id', 'guid']);
            $table->foreign('source_id')->references('id')->on('rss_sources')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('set null');
        });

        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->nullable()->index();
            $table->string('path')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id', 100)->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('device_type')->nullable();
            $table->string('country_code', 3)->nullable();
            $table->date('date')->index();
            $table->timestamps();

            $table->index(['date', 'post_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        Schema::create('analytics_daily', function (Blueprint $table) {
            $table->id();
            $table->date('date')->index();
            $table->unsignedBigInteger('post_id')->nullable()->index();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('unique_visitors')->default(0);
            $table->unsignedBigInteger('new_subscribers')->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['date', 'post_id']);
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_url')->unique();
            $table->string('to_url');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('hits')->default(0);
            $table->timestamps();

            $table->index('from_url');
        });

        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('name');
            $table->string('slug');
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('media_folders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('redirects');
        Schema::dropIfExists('analytics_daily');
        Schema::dropIfExists('page_views');
        Schema::dropIfExists('rss_items');
        Schema::dropIfExists('rss_sources');
    }
};
