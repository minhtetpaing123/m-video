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
        Schema::table('posts', function (Blueprint $table) {
            // Title - for video title (max 200 characters)
            if (!Schema::hasColumn('posts', 'title')) {
                $table->string('title', 200)->nullable()->after('content');
            }
            // Description - for video description/story
            if (!Schema::hasColumn('posts', 'description')) {
                $table->text('description')->nullable()->after('title');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });
    }
};