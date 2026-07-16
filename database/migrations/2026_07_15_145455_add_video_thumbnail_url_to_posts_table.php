<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (!Schema::hasColumn('posts', 'video_thumbnail_url')) {
                $table->text('video_thumbnail_url')->nullable()->after('video_thumbnail');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'video_thumbnail_url')) {
                $table->dropColumn('video_thumbnail_url');
            }
        });
    }
};