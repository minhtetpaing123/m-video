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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            
            // Follow ခံရမယ့်သူ (Target User ID)
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Follow သွားလုပ်တဲ့သူ (Follower User ID)
            $table->foreignId('follower_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            $table->timestamps();

            // တစ်ယောက်ကို နှစ်ခါ duplicate follow မဖြစ်အောင် ထိန်းပေးထားခြင်း
            $table->unique(['user_id', 'follower_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
