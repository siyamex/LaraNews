<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->enum('type', [
                'article', 'gallery', 'video', 'audio', 'poll', 'trivia_quiz',
                'personality_quiz', 'recipe', 'event', 'sorted_list', 'live_blog'
            ])->default('article');
            $table->enum('status', ['draft', 'pending', 'published', 'scheduled', 'archived'])->default('draft')->index();
            $table->string('featured_image')->nullable();
            $table->string('featured_image_caption')->nullable();
            $table->string('featured_image_alt')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_breaking')->default(false)->index();
            $table->boolean('is_trending')->default(false)->index();
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_premium')->default(false)->index();
            $table->enum('paywall_type', ['none', 'hard', 'soft', 'fade'])->default('none');
            $table->unsignedInteger('free_paragraphs')->default(3);
            $table->unsignedBigInteger('views_count')->default(0)->index();
            $table->unsignedBigInteger('comments_count')->default(0);
            $table->unsignedBigInteger('reactions_count')->default(0);
            $table->unsignedBigInteger('shares_count')->default(0);
            $table->unsignedBigInteger('bookmarks_count')->default(0);
            $table->unsignedSmallInteger('reading_time')->default(0);
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamp('scheduled_at')->nullable();
            $table->json('meta')->nullable();
            $table->json('schema_markup')->nullable();
            $table->json('content_blocks')->nullable();
            $table->string('source_url')->nullable();
            $table->string('source_name')->nullable();
            $table->boolean('allow_comments')->default(true);
            $table->boolean('allow_reactions')->default(true);
            $table->string('ai_summary')->nullable();
            $table->json('ai_tags')->nullable();
            $table->unsignedBigInteger('poll_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });

        Schema::create('post_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->index();
            $table->string('locale', 10)->index();
            $table->string('title');
            $table->string('slug')->index();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->json('faq')->nullable();
            $table->timestamps();

            $table->unique(['post_id', 'locale']);
            $table->unique(['locale', 'slug']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });

        Schema::create('post_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('locale', 10)->default('dv');
            $table->string('title');
            $table->longText('content');
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('post_authors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('post_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->enum('role', ['author', 'co_author', 'contributor', 'translator'])->default('author');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['post_id', 'user_id']);
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_authors');
        Schema::dropIfExists('post_revisions');
        Schema::dropIfExists('post_translations');
        Schema::dropIfExists('posts');
    }
};
