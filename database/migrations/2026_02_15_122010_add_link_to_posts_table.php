<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('link')->nullable()->after('video');
            $table->string('link_title')->nullable()->after('link');
            $table->string('link_thumbnail')->nullable()->after('link_title');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['link', 'link_title', 'link_thumbnail']);
        });
    }
};