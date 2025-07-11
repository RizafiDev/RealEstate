<?php

namespace Database\Seeders;

use App\Models\Lead;
use App\Models\Customer;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeadSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $projects = Project::all();
        $units = Unit::where('status', 'available')->get();
        $users = User::all();

        Lead::factory(150)->create()->each(function ($lead) use ($customers, $projects, $units, $users) {
            $lead->update([
                'customer_id' => $customers->random()->id,
                'project_id' => fake()->boolean(70) ? $projects->random()->id : null,
                'unit_id' => fake()->boolean(30) ? $units->random()->id : null,
                'sales_agent_id' => fake()->boolean(80) ? $users->random()->id : null,
            ]);
        });
    }
}