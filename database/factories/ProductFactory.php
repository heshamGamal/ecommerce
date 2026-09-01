<?php

namespace Database\Factories;

use App\Domain\Models\Category;
use App\Domain\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return ['category_id' => Category::factory(), 'name' => $name, 'slug' => str($name)->slug(), 'description' => fake()->sentence(), 'price' => fake()->randomFloat(2, 10, 1000), 'sku' => fake()->unique()->numerify('SKU#####'), 'stock' => fake()->numberBetween(0, 100), 'is_active' => true, 'is_featured' => false];
    }
}
