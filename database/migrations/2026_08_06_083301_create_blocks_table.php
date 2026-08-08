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
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            
            // 1. Core Foreign Keys
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Block ပြုလုပ်သူ (Blocker)
            $table->foreignId('blocked_user_id')->constrained('users')->cascadeOnDelete(); // Block အလုပ်ခံရသူ (Blocked)
            
            // 2. Block Type (စနစ်တစ်ခုလုံး Block မည်လား / Chat တစ်ခုတည်း Block မည်လား)
            $table->enum('type', ['full', 'chat_only'])->default('full');

            // 3. Block Reason (Block ရသည့် အကြောင်းအရင်း - e.g., 'spam', 'harassment', 'other')
            $table->string('reason')->nullable();

            // 4. Cooldown / History Tracking (Unblock လုပ်ခဲ့သည့် အချိန်ကို မှတ်ထားရန်)
            $table->timestamp('unblocked_at')->nullable();

            $table->timestamps();

            // Unique constraint (ID ထပ်မဝင်စေရန်)
            $table->unique(['user_id', 'blocked_user_id']);
            
            // Query မြန်ဆန်စေရန် Index သတ်မှတ်ခြင်း
            $table->index(['user_id', 'blocked_user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};
