<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\post;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'post_id' => post::inRandomOrder()->first()?->id ?? post::factory(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'comment' => $this->faker->paragraph(rand(1, 3)),
        ];
    }
}
