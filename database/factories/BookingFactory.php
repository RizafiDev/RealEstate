<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        $unitPrice = fake()->numberBetween(200000000, 2000000000);
        $discountAmount = fake()->numberBetween(0, $unitPrice * 0.1);
        $totalPrice = $unitPrice - $discountAmount;
        $bookingFee = $totalPrice * 0.1;

        return [
            'booking_number' => fake()->unique()->regexify('BK[0-9]{8}'),
            'customer_id' => Customer::factory(),
            'unit_id' => Unit::factory(),
            'sales_agent_id' => User::factory(),
            'booking_date' => fake()->dateTimeBetween('-6 months', 'now'),
            'booking_fee' => $bookingFee,
            'unit_price' => $unitPrice,
            'discount_amount' => $discountAmount,
            'total_price' => $totalPrice,
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'payment_method' => fake()->randomElement(['cash', 'kpr', 'installment']),
            'notes' => fake()->sentence(),
            'expired_at' => fake()->dateTimeBetween('now', '+30 days'),
        ];
    }
}