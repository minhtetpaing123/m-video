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
        Schema::table('follows', function (Blueprint $table) {
            // ၁။ Status (Private account အတွက် Request လက်ခံ/မခံ - pending, accepted, rejected)
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('accepted')->after('follower_id');
            
            // ၂။ Notification (ဒီ User Post တင်ရင် Noti ယူ/မယူ အဖွင့်အပိတ်)
            $table->boolean('notify')->default(true)->after('status');
            
            // ၃။ Favorite / Close Friends (ချစ်ခင်ရသူ စာရင်းထဲ ထည့်/မထည့်)
            $table->boolean('is_favorite')->default(false)->after('notify');
            
            // ၄။ Mute (Unfollow မလုပ်ဘဲ Post များ ခေတ္တ ဖျောက်ထားရန်)
            $table->boolean('is_muted')->default(false)->after('is_favorite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follows', function (Blueprint $table) {
            $table->dropColumn(['status', 'notify', 'is_favorite', 'is_muted']);
        });
    }
};
