<?php

namespace Database\Factories;

use App\Domain\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);
        return ['name' => $name, 'slug' => str($name)->slug(), 'description' => fake()->optional()->sentence(), 'is_active' => true, 'sort_order' => 0];
    }
}
