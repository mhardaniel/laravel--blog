<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'author_id' => User::factory(),
            'slug' => fn (array $attrs) => Str::slug($attrs['title']),
            'title' => fake()->unique()->sentence(4),
            'description' => fake()->paragraph(),
            'body' => fake()->text(),
            'created_at' => function (array $attributes) {
                $user = User::find($attributes['author_id']);

                return fake()->dateTimeBetween($user->created_at);
            },
            'updated_at' => function (array $attributes) {
                $createdAt = $attributes['created_at'];

                return fake()->optional(25, $createdAt)
                    ->dateTimeBetween($createdAt);
            },
        ];
    }
}
