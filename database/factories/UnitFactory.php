<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['available', 'booked', 'sold']);
        $basePrice = fake()->numberBetween(200000000, 2000000000);

        return [
            'project_id' => Project::factory(),
            'unit_type_id' => UnitType::factory(),
            'unit_code' => fake()->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'status' => $status,
            'price' => $basePrice,
            'discount_price' => $status === 'available' ? $basePrice * 0.9 : null,
            'facing' => fake()->randomElement(['Utara', 'Selatan', 'Timur', 'Barat', 'Tenggara', 'Barat Daya']),
            'certificate' => fake()->randomElement(['SHM', 'HGB', 'SHGB']),
            'cash_hard_percentage' => fake()->numberBetween(20, 40),
            'cash_tempo_percentage' => fake()->numberBetween(10, 30),
            'description' => fake()->paragraphs(2, true),
            'notes' => fake()->sentence(),
            'images' => json_encode([
                fake()->imageUrl(800, 600, 'house'),
                fake()->imageUrl(800, 600, 'house'),
            ]),
        ];
    }
}