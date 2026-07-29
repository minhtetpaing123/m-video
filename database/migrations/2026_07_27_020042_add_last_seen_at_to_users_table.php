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
            // ✅ bio column မရှိရင် အရင်ထည့်မယ်
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('username');
            }
            
            // ✅ last_seen_at column ထည့်မယ်
            $table->timestamp('last_seen_at')->nullable()->after('bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_seen_at');
            // bio ကို မဖျက်ဘူး (အရင်ရှိပြီးသား column ဆိုရင် မဖျက်တာပိုကောင်းတယ်)
        });
    }
};