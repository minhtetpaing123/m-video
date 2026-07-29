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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            
            // Callers (ခေါ်ဆိုသူ နှင့် လက်ခံသူ)
            $table->foreignId('caller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            
            // WebRTC Unique Room ID / Channel Name
            $table->string('channel_name')->unique();
            
            // Call Type (Voice / Video Call)
            $table->enum('type', ['voice', 'video'])->default('voice');
            
            // Real-time Status Tracking
            $table->enum('status', [
                'calling',      // ဖုန်းစခေါ်နေချိန်
                'ringing',      // တစ်ဖက်လူထံ ဖုန်းဝင်နေချိန်
                'accepted',     // ဖုန်းကို လက်ခံကိုင်လိုက်ချိန်
                'rejected',     // ဖုန်းကို ငြင်းပယ်လိုက်ချိန်
                'missed',       // ဖုန်းမကိုင်ဘဲ လွတ်သွားချိန်
                'ended',        // ပုံမှန် စကားပြောပြီး ဖုန်းချလိုက်ချိန်
                'failed'        // လိုင်းကျသွားခြင်း သို့မဟုတ် အမှားအယွင်းဖြစ်ချိန်
            ])->default('calling');
            
            // Call Termination Reasons (အသေးစိတ် အခြေအနေ)
            $table->enum('end_reason', [
                'completed',       // ပြောပြီးလို့ ချတာ
                'declined',        // ငြင်းလိုက်တာ
                'rejected',        // ငြင်းပယ်လိုက်တာ
                'busy',            // တစ်ဖက်လူ လိုင်းမအားတာ
                'cancelled',       // ခေါ်သူဘက်မှ ပြန်ဖျက်တာ
                'network_failed',  // လိုင်းကျတာ
                'timeout'          // မကိုင်ဘဲ ကြာသွားတာ
            ])->nullable();
            
            // Call Duration & Timestamps
            $table->unsignedInteger('duration')->default(0)->comment('Duration in seconds');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            
            // Security & Analytics
            $table->boolean('is_encrypted')->default(true); // E2EE Support
            $table->unsignedTinyInteger('quality_rating')->nullable()->comment('User feedback rating 1-5');
            
            $table->timestamps();
            
            // Fast Query Indexes
            $table->index(['caller_id', 'receiver_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
