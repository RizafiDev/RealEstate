<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DeveloperFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'logo' => fake()->imageUrl(200, 200, 'business'),
            'description' => fake()->paragraphs(3, true),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->companyEmail(),
            'website' => fake()->url(),
            'status' => fake()->randomElement(['active', 'inactive']),
            'established_year' => fake()->numberBetween(1980, 2020),
        ];
    }
}