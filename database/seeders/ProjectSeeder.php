<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Developer;
use App\Models\Location;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $developers = Developer::all();
        $locations = Location::all();

        Project::factory(30)->create([
            'developer_id' => $developers->random()->id,
            'location_id' => $locations->random()->id,
        ]);
    }
}