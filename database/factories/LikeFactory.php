<?php

namespace Database\Factories;
use App\Models\post;
use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\like>
 */
class LikeFactory extends Factory
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
            'like' => $this->faker->boolean()
        ];
    }
}
