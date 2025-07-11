<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitTypeFactory extends Factory
{
    public function definition(): array
    {
        $types = ['Tipe 36', 'Tipe 45', 'Tipe 54', 'Tipe 60', 'Tipe 70', 'Tipe 90'];
        $features = [
            'Carport',
            'Taman Depan',
            'Dapur Bersih',
            'Kamar Mandi Dalam',
            'Ruang Keluarga',
            'Ruang Tamu',
            'Balkon',
            'Gudang'
        ];

        $specifications = [
            'Carport' => '1 mobil',
            'Listrik' => '2200 VA',
            'Air' => 'PDAM',
            'Lantai' => 'Keramik',
            'Dinding' => 'Bata merah',
            'Atap' => 'Genteng beton',
        ];

        return [
            'project_id' => Project::factory(),
            'name' => fake()->randomElement($types),
            'description' => fake()->paragraphs(2, true),
            'land_area' => fake()->numberBetween(60, 200),
            'building_area' => fake()->numberBetween(36, 150),
            'bedrooms' => fake()->numberBetween(2, 4),
            'bathrooms' => fake()->numberBetween(1, 3),
            'garages' => fake()->numberBetween(0, 2), // Changed from 'garage' to 'garages'
            'floors' => fake()->numberBetween(1, 2),
            'specifications' => json_encode($specifications),
            'floor_plan' => fake()->imageUrl(600, 400, 'architecture'),
        ];
    }
}