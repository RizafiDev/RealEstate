<?php

namespace Database\Seeders;

use App\Models\Campaign;
use App\Models\Project;
use App\Models\UnitType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    use HasFactory;
    public function run(): void
    {
        $projects = Project::all();
        $unitTypes = UnitType::all();

        Campaign::factory(20)->create()->each(function ($campaign) use ($projects, $unitTypes) {
            $campaign->update([
                'project_id' => fake()->boolean(80) ? $projects->random()->id : null,
                'unit_type_id' => fake()->boolean(60) ? $unitTypes->random()->id : null,
            ]);
        });
    }
}