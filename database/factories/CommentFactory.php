<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
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
            'article' => Article::factory(),
            'author' => User::factory(),
            'body' => fake()->sentence(),
            'created_at' => function (array $attributes) {
                $article = Article::find($attributes['article']);

                return fake()->dateTimeBetween($article->created_at);
            },
            'updated_at' => function (array $attributes) {
                return $attributes['created_at'];
            },
        ];
    }
}
