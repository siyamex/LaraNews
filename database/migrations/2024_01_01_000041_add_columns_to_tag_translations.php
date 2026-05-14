<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            if (! Schema::hasColumn('tag_translations', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (! Schema::hasColumn('tag_translations', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('description');
            }
            if (! Schema::hasColumn('tag_translations', 'meta_description')) {
                $table->string('meta_description')->nullable()->after('meta_title');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tag_translations', function (Blueprint $table) {
            $table->dropColumn(array_filter(['slug', 'meta_title', 'meta_description'], fn($col) => Schema::hasColumn('tag_translations', $col)));
        });
    }
};
