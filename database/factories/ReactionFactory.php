<?php

namespace Database\Factories;

use App\Models\Reaction;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reaction>
 */
class ReactionFactory extends Factory
{
    protected $model = Reaction::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'comment_id' => null,
            'type' => fake()->randomElement(['like', 'love', 'care', 'haha', 'wow', 'sad', 'angry']),
            'created_at' => fake()->dateTimeBetween('-30 days', 'now'),
            'updated_at' => now(),
        ];
    }

    public function forComment()
    {
        return $this->state(function (array $attributes) {
            return [
                'post_id' => null,
                'comment_id' => \App\Models\Comment::factory(),
            ];
        });
    }
}