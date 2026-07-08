<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Users အရင်ဖန်တီးမယ် (၁၀ယောက်)
        $users = User::factory(10)->create();

        // Posts ၃၀ ဖန်တီးမယ်
        Post::factory(30)
            ->withImage() // image ပါတဲ့ post တချို့
            ->create(['user_id' => $users->random()->id])
            ->each(function ($post) use ($users) {
                // Comments ၃-၈ ခုစီဖန်တီးမယ်
                $comments = Comment::factory(rand(3, 8))->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id
                ]);

                // Reactions ၅-၁၅ ခုစီဖန်တီးမယ်
                $reactionCount = rand(5, 15);
                for ($i = 0; $i < $reactionCount; $i++) {
                    Reaction::factory()->create([
                        'user_id' => $users->random()->id,
                        'post_id' => $post->id,
                    ]);
                }

                // Post ရဲ့ likes_count ကို update လုပ်မယ်
                $post->update([
                    'likes_count' => $post->reactions()->count(),
                    'comments_count' => $post->comments()->count(),
                ]);
            });

        // Text-only posts (image မပါတဲ့ post တချို့)
        Post::factory(20)->create([
            'user_id' => $users->random()->id,
            'image' => null,
            'video' => null,
        ])->each(function ($post) use ($users) {
            Comment::factory(rand(1, 5))->create([
                'post_id' => $post->id,
                'user_id' => $users->random()->id
            ]);

            $reactionCount = rand(3, 10);
            for ($i = 0; $i < $reactionCount; $i++) {
                Reaction::factory()->create([
                    'user_id' => $users->random()->id,
                    'post_id' => $post->id,
                ]);
            }

            $post->update([
                'likes_count' => $post->reactions()->count(),
                'comments_count' => $post->comments()->count(),
            ]);
        });

        $this->command->info('Posts seeded successfully!');
        $this->command->info('Total Posts: ' . Post::count());
        $this->command->info('Total Comments: ' . Comment::count());
        $this->command->info('Total Reactions: ' . Reaction::count());
    }
}