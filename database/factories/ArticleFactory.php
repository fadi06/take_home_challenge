<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'category_id' => random_int(1, 20),
            'description' => $this->faker->paragraph,
            'content' => $this->faker->text,
            'country' => 'us',
            'language' => 'en',
            'author' => $this->faker->name,
            'image' => $this->faker->imageUrl(),
            'source' => 'Test Source',
            'url' => $this->faker->url,
            'feed' => 'newsapi',
            'published_at' => now(),
        ];
    }
}
