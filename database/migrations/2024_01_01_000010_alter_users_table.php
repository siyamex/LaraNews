<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('avatar')->nullable()->after('username');
            $table->string('bio', 500)->nullable()->after('avatar');
            $table->string('phone', 20)->nullable()->after('bio');
            $table->string('locale', 10)->default('dv')->after('phone');
            $table->string('timezone')->default('Indian/Maldives')->after('locale');
            $table->string('theme_preference')->default('auto')->after('timezone');
            $table->boolean('is_active')->default(true)->after('theme_preference');
            $table->boolean('is_verified_journalist')->default(false)->after('is_active');
            $table->timestamp('last_seen_at')->nullable()->after('is_verified_journalist');
            $table->string('two_factor_secret')->nullable()->after('last_seen_at');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');
            $table->string('provider')->nullable()->after('two_factor_confirmed_at');
            $table->string('provider_id')->nullable()->after('provider');
            $table->json('notification_preferences')->nullable()->after('provider_id');
            $table->unsignedBigInteger('followers_count')->default(0)->after('notification_preferences');
            $table->unsignedBigInteger('following_count')->default(0)->after('followers_count');
            $table->unsignedBigInteger('posts_count')->default(0)->after('following_count');
            $table->string('website')->nullable()->after('posts_count');
            $table->string('twitter_handle')->nullable()->after('website');
            $table->string('facebook_url')->nullable()->after('twitter_handle');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'avatar', 'bio', 'phone', 'locale', 'timezone',
                'theme_preference', 'is_active', 'is_verified_journalist',
                'last_seen_at', 'two_factor_secret', 'two_factor_recovery_codes',
                'two_factor_confirmed_at', 'provider', 'provider_id',
                'notification_preferences', 'followers_count', 'following_count',
                'posts_count', 'website', 'twitter_handle', 'facebook_url', 'deleted_at',
            ]);
        });
    }
};
