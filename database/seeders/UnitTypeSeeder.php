<?php

namespace Database\Seeders;

use App\Models\UnitType;
use App\Models\Project;
use Illuminate\Database\Seeder;

class UnitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            UnitType::factory(rand(2, 5))->create([
                'project_id' => $project->id,
            ]);
        }
    }
}