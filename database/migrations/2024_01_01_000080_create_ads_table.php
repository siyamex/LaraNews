<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ad_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->enum('placement', [
                'header', 'sidebar', 'in_content', 'footer',
                'popup', 'sticky', 'before_content', 'after_content',
                'between_posts', 'mobile_banner'
            ]);
            $table->unsignedSmallInteger('width')->nullable();
            $table->unsignedSmallInteger('height')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_responsive')->default(true);
            $table->boolean('desktop_only')->default(false);
            $table->boolean('mobile_only')->default(false);
            $table->timestamps();
        });

        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id')->index();
            $table->string('name');
            $table->enum('type', ['image', 'html', 'adsense', 'amp'])->default('image');
            $table->text('content')->nullable();
            $table->string('image')->nullable();
            $table->string('link_url')->nullable();
            $table->string('link_target')->default('_blank');
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->unsignedBigInteger('impressions_count')->default(0);
            $table->unsignedBigInteger('clicks_count')->default(0);
            $table->unsignedBigInteger('impression_limit')->nullable();
            $table->integer('weight')->default(100);
            $table->json('targeting')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('zone_id')->references('id')->on('ad_zones')->onDelete('cascade');
        });

        Schema::create('ad_impressions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ad_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id', 100)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('clicked')->default(false);
            $table->timestamp('clicked_at')->nullable();
            $table->string('page_url')->nullable();
            $table->timestamps();

            $table->foreign('ad_id')->references('id')->on('ads')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ad_impressions');
        Schema::dropIfExists('ads');
        Schema::dropIfExists('ad_zones');
    }
};
