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
        Schema::table('users', function (Blueprint $table) {
            // 🔊 1. Notification Delivery Channels (နိုတီ ပို့ပေးမည့် လမ်းကြောင်းများ)
            $table->boolean('notify_sound')->default(true)->after('remember_token');
            $table->boolean('notify_in_app')->default(true);      // Web/In-App Banner
            $table->boolean('notify_email')->default(true);       // Email Notification
            $table->boolean('notify_push')->default(true);        // Browser / Mobile Web Push

            // 🎯 2. Social Interactions (လှုပ်ရှားမှု အမျိုးအစားအလိုက် ဖွင့်/ပိတ်)
            $table->boolean('notify_comments')->default(true);    // Comment
            $table->boolean('notify_replies')->default(true);     // Reply
            $table->boolean('notify_likes')->default(true);       // Like / Reaction
            $table->boolean('notify_mentions')->default(true);    // Tag / Mention
            $table->boolean('notify_follows')->default(true);     // Follow / Unfollow
            $table->boolean('notify_friend_requests')->default(true); // Friend Request
            $table->boolean('notify_messages')->default(true);    // Direct Messages (Chat)

            // 📢 3. System & Security Alerts (စနစ်ဆိုင်ရာ သတိပေးချက်များ)
            $table->boolean('notify_system_announcements')->default(true);
            $table->boolean('notify_security_alerts')->default(true);

            // 🌙 4. Quiet Hours / Do Not Disturb (ညဘက် အသံ/နိုတီ ပိတ်ထားမည့် အချိန်သတ်မှတ်ချက်)
            $table->boolean('quiet_hours_enabled')->default(false);
            $table->time('quiet_hours_start')->nullable(); // ဥပမာ - 22:00:00
            $table->time('quiet_hours_end')->nullable();   // ဥပမာ - 07:00:00

            // 📦 5. Future Expansion (အနာဂတ်တွင် Database Schema မပြင်ဘဲ စိတ်ကြိုက် Data သိမ်းနိုင်ရန် JSON Column)
            $table->json('notification_settings')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_sound',
                'notify_in_app',
                'notify_email',
                'notify_push',
                'notify_comments',
                'notify_replies',
                'notify_likes',
                'notify_mentions',
                'notify_follows',
                'notify_friend_requests',
                'notify_messages',
                'notify_system_announcements',
                'notify_security_alerts',
                'quiet_hours_enabled',
                'quiet_hours_start',
                'quiet_hours_end',
                'notification_settings',
            ]);
        });
    }
};
