<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->address(),
            'date_of_birth' => fake()->dateTimeBetween('-60 years', '-20 years'),
            'occupation' => fake()->jobTitle(),
            'monthly_income' => fake()->numberBetween(5000000, 50000000),
            'identity_number' => fake()->numerify('################'),
            'identity_type' => fake()->randomElement(['ktp', 'passport', 'sim']),
            'marital_status' => fake()->randomElement(['single', 'married', 'divorced', 'widowed']),
            'source' => fake()->randomElement(['website', 'referral', 'advertisement', 'walk_in', 'social_media']),
            'notes' => fake()->sentence(),
        ];
    }
}