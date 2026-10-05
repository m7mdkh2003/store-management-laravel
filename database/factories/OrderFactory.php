<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 3);
        $price = fake()->randomFloat(2, 10, 500);

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'quantity' => $quantity,
            'total_price' => $price * $quantity,
            'status' => Order::STATUS_PENDING,
        ];
    }
}
