<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('video_1080')->nullable()->after('video');
            $table->string('video_720')->nullable()->after('video_1080');
            $table->string('video_480')->nullable()->after('video_720');
            $table->string('video_360')->nullable()->after('video_480');
            $table->string('video_240')->nullable()->after('video_360');
            $table->string('video_144')->nullable()->after('video_240');
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'video_1080',
                'video_720',
                'video_480',
                'video_360',
                'video_240',
                'video_144'
            ]);
        });
    }
};