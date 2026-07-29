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
        Schema::table('messages', function (Blueprint $table) {
            // 🔥 Reply အတွက် မူရင်း Message ID
            $table->foreignId('reply_to_id')->nullable()->after('message')->constrained('messages')->onDelete('set null');
            
            // 🔥 Edit လုပ်ထားခြင်း ရှိ/မရှိ
            $table->boolean('is_edited')->default(false)->after('is_read');

            // 🔥 Delete/Unsend ပြုလုပ်ရန် Column များ
            $table->boolean('deleted_for_everyone')->default(false)->after('is_edited');
            $table->boolean('deleted_for_sender')->default(false)->after('deleted_for_everyone');
            $table->boolean('deleted_for_receiver')->default(false)->after('deleted_for_sender');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to_id']);
            $table->dropColumn([
                'reply_to_id', 
                'is_edited', 
                'deleted_for_everyone', 
                'deleted_for_sender', 
                'deleted_for_receiver'
            ]);
        });
    }
};
