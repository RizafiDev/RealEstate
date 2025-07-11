<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            DeveloperSeeder::class,
            LocationSeeder::class,
            ProjectSeeder::class,
            UnitTypeSeeder::class,
            UnitSeeder::class,
            CustomerSeeder::class,
            LeadSeeder::class,
            BookingSeeder::class,
            CampaignSeeder::class,
        ]);
    }
}
