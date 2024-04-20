<?php

namespace Database\Factories\v1;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Chapter>
 */
class ChapterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'manga_id' => fake()->numberBetween(1, 200),
            'number' => fake()->numberBetween(1, 2000),
            'title' => fake()->realTextBetween(15, 40),
        ];
    }
}
