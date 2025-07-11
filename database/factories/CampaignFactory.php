<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\UnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['discount', 'cashback', 'free_facilities', 'special_price']);

        return [
            'name' => fake()->words(3, true) . ' Campaign',
            'description' => fake()->paragraphs(2, true),
            'type' => $type,
            'project_id' => Project::factory(),
            'unit_type_id' => fake()->boolean(60) ? UnitType::factory() : null,
            'discount_percentage' => $type === 'discount' ? fake()->numberBetween(5, 25) : null,
            'discount_amount' => $type === 'cashback' ? fake()->numberBetween(5000000, 50000000) : null,
            'start_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'end_date' => fake()->dateTimeBetween('now', '+6 months'),
            'max_usage' => fake()->numberBetween(10, 100),
            'current_usage' => fake()->numberBetween(0, 20),
            'is_active' => fake()->boolean(80),
        ];
    }
}