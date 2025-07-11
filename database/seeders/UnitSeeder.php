<?php

namespace Database\Seeders;

use App\Models\Unit;
use App\Models\Project;
use App\Models\UnitType;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $projects = Project::with('unitTypes')->get();

        foreach ($projects as $project) {
            foreach ($project->unitTypes as $unitType) {
                Unit::factory(rand(5, 20))->create([
                    'project_id' => $project->id,
                    'unit_type_id' => $unitType->id,
                ]);
            }
        }
    }
}