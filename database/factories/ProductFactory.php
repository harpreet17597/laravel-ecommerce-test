<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name'        => $this->faker->word(),
            'description' => $this->faker->text(200),
            'price'       => $this->faker->randomFloat(2, 100, 10000), //Random price between 100–10000
            'status'      => Product::AVAILABLE_PRODUCT,
        ];
    }
}
