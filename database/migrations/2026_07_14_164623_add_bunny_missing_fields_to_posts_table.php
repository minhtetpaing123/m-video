<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Only add columns that don't exist yet
            if (!Schema::hasColumn('posts', 'video_original')) {
                $table->string('video_original')->nullable();
            }
            
            if (!Schema::hasColumn('posts', 'video_path')) {
                $table->string('video_path')->nullable();
            }
            
            if (!Schema::hasColumn('posts', 'video_cdn_url')) {
                $table->text('video_cdn_url')->nullable();
            }
            
            if (!Schema::hasColumn('posts', 'video_thumbnail_url')) {
                $table->text('video_thumbnail_url')->nullable();
            }
            
            if (!Schema::hasColumn('posts', 'video_duration')) {
                $table->integer('video_duration')->nullable();
            }
            
            if (!Schema::hasColumn('posts', 'video_size')) {
                $table->integer('video_size')->nullable();
            }
            
            if (!Schema::hasColumn('posts', 'video_status')) {
                $table->string('video_status')->nullable()->default('pending');
            }
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $columns = ['video_original', 'video_path', 'video_cdn_url', 
                        'video_thumbnail_url', 'video_duration', 'video_size', 'video_status'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('posts', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};