<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. မင်းရဲ့ ရှိပြီးသား notifications table ထဲသို့ လိုအပ်နေသော columns များ သီးသန့်ဖြည့်ခြင်း
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'action_url')) {
                $table->string('action_url')->nullable()->after('data');
            }
            if (!Schema::hasColumn('notifications', 'priority')) {
                $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal')->after('action_url');
            }
            if (!Schema::hasColumn('notifications', 'group_key')) {
                $table->string('group_key')->nullable()->index()->after('priority');
            }
            if (!Schema::hasColumn('notifications', 'expires_at')) {
                $table->timestamp('expires_at')->nullable()->after('group_key');
            }
            if (!Schema::hasColumn('notifications', 'deleted_at')) {
                $table->softDeletes()->after('expires_at');
            }
        });

        // 2. Browser Push Notification အတွက် push_subscriptions table မရှိသေးပါက အသစ်ဆောက်ခြင်း
        if (!Schema::hasTable('push_subscriptions')) {
            Schema::create('push_subscriptions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('endpoint', 500)->unique();
                $table->string('public_key')->nullable();
                $table->string('auth_token')->nullable();
                $table->string('content_encoding')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['action_url', 'priority', 'group_key', 'expires_at', 'deleted_at']);
        });
        Schema::dropIfExists('push_subscriptions');
    }
};
