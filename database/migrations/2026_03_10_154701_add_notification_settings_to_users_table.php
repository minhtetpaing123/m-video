<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('email_notifications')->default(true)->after('remember_token');
            $table->boolean('push_notifications')->default(true)->after('email_notifications');
            $table->boolean('comment_notifications')->default(true)->after('push_notifications');
            $table->boolean('like_notifications')->default(true)->after('comment_notifications');
            $table->boolean('follow_notifications')->default(true)->after('like_notifications');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'email_notifications',
                'push_notifications',
                'comment_notifications',
                'like_notifications',
                'follow_notifications'
            ]);
        });
    }
};