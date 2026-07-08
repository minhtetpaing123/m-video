<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => fake()->paragraphs(rand(1, 3), true),
            'image' => null,
            'video' => null,
            'privacy' => fake()->randomElement(['public', 'friends', 'onlyme']),
            'likes_count' => rand(0, 1000),
            'comments_count' => rand(0, 100),
            'shares_count' => rand(0, 50),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }

    public function withImage()
    {
        return $this->state(function (array $attributes) {
            return [
                'image' => 'posts/images/test-' . rand(1, 10) . '.jpg',
            ];
        });
    }

    public function withVideo()
    {
        return $this->state(function (array $attributes) {
            return [
                'video' => 'posts/videos/test-' . rand(1, 5) . '.mp4',
            ];
        });
    }
}