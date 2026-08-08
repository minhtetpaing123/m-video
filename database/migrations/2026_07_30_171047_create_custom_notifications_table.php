<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ဇယားဟောင်း ရှိခဲ့ပါက ဖျက်ပစ်မည်
        Schema::dropIfExists('notifications');

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // 1. Users Relationship (လက်ခံမည့်သူ နှင့် ပြုလုပ်သူ)
            $table->foreignId('user_id')->comment('Notification လက်ခံမည့်သူ (Recipient)')->constrained('users')->cascadeOnDelete();
            $table->foreignId('from_user_id')->nullable()->comment('Notification ပြုလုပ်သူ (Sender/Actor)')->constrained('users')->cascadeOnDelete();

            // 2. Entities Relationship (Post, Comment, Reply)
            $table->foreignId('post_id')->nullable()->constrained('posts')->cascadeOnDelete();
            $table->unsignedBigInteger('comment_id')->nullable()->index();
            $table->unsignedBigInteger('reply_id')->nullable()->index();

            // 3. Notification Types & Reactions
            $table->string('type', 50)->index()->comment('like, reaction, comment, reply, follow, mention, system');
            $table->string('reaction_type', 30)->nullable()->comment('like, love, care, haha, wow, sad, angry');

            // 4. Content & Payload (စာသားများ နှင့် Links)
            $table->string('title')->nullable()->comment('System Notification များအတွက် ခေါင်းစဉ်');
            $table->text('content_snippet')->nullable()->comment('Comment သို့မဟုတ် Post စာသားအတိုချုပ်');
            $table->string('action_url')->nullable()->comment('နှိပ်လိုက်လျှင် သွားရမည့် Redirect Link');
            $table->string('image_url')->nullable()->comment('Custom Icon သို့မဟုတ် Thumbnail ပုံ URL');

            // 5. Read Status
            $table->boolean('is_read')->default(false)->index();
            $table->timestamp('read_at')->nullable();

            // 6. Grouping & Aggregation Features (ဥပမာ - "A and 5 others liked your post")
            $table->unsignedInteger('group_count')->default(1);
            $table->string('group_key')->nullable()->index();

            $table->timestamps();

            // Indexing for Fast Query Performance
            $table->index(['user_id', 'is_read', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
