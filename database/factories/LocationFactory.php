<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    public function definition(): array
    {
        $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Yogyakarta', 'Semarang', 'Medan', 'Palembang'];
        $provinces = ['DKI Jakarta', 'Jawa Barat', 'Jawa Timur', 'DI Yogyakarta', 'Jawa Tengah', 'Sumatera Utara', 'Sumatera Selatan'];

        return [
            'name' => fake()->cityPrefix() . ' ' . fake()->citySuffix(),
            'type' => fake()->randomElement(['city', 'district', 'subdistrict']),
            'city' => fake()->randomElement($cities),
            'province' => fake()->randomElement($provinces),
            'address' => fake()->address(),
            'postal_code' => fake()->postcode(),
            'latitude' => fake()->latitude(-8, -6),
            'longitude' => fake()->longitude(106, 112),
            'description' => fake()->sentence(),
        ];
    }
}