<?php

namespace Database\Factories;

use App\Models\Developer;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $facilities = [
            'Swimming Pool',
            'Gym',
            'Playground',
            'Security 24/7',
            'CCTV',
            'Garden',
            'Jogging Track',
            'Community Hall',
            'Mosque',
            'Parking Area'
        ];

        return [
            'developer_id' => Developer::factory(),
            'location_id' => Location::factory(),
            'name' => fake()->words(3, true) . ' Residence',
            'description' => fake()->paragraphs(4, true),
            'address' => fake()->address(),
            'status' => fake()->randomElement(['planning', 'development', 'ready', 'completed']),
            'start_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'estimated_completion' => fake()->dateTimeBetween('now', '+3 years'),
            'total_units' => fake()->numberBetween(50, 500),
            'facilities' => json_encode(fake()->randomElements($facilities, fake()->numberBetween(3, 8))),
            'phone' => fake()->phoneNumber(),
            'sales_phone' => fake()->phoneNumber(),
            'sales_email' => fake()->email(),
            'images' => json_encode([
                fake()->imageUrl(800, 600, 'house'),
                fake()->imageUrl(800, 600, 'house'),
                fake()->imageUrl(800, 600, 'house'),
            ]),
            'master_plan' => fake()->imageUrl(1200, 800, 'architecture'),
            'brochure_url' => fake()->url(),
        ];
    }
}