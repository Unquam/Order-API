<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \JsonException
     */
    public function definition(): array
    {
        return [
            'total_amount' => $this->faker->randomFloat(2, 50, 500),
            'products' => json_encode([
                [
                    'product_name' => $this->faker->word,
                    'quantity' => $this->faker->numberBetween(1, 5),
                    'price' => $this->faker->randomFloat(2, 10, 100),
                ],
            ], JSON_THROW_ON_ERROR),
            'status' => 'pending'
        ];
    }
}
