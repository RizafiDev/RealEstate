<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Project;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'source' => fake()->randomElement(['website', 'referral', 'social_media', 'advertisement']),
            'notes' => fake()->sentence(),
            'assigned_to' => User::factory(),
            'customer_id' => Customer::factory(),
            'project_id' => fake()->boolean(70) ? Project::factory() : null,
            'unit_id' => fake()->boolean(30) ? Unit::factory() : null,
            'sales_agent_id' => fake()->boolean(80) ? User::factory() : null,
            'status' => fake()->randomElement(['new', 'contacted', 'qualified', 'proposal', 'negotiation', 'closed_won', 'closed_lost']),
            'priority' => fake()->randomElement(['low', 'medium', 'high']),
            'budget_min' => fake()->numberBetween(100000000, 500000000),
            'budget_max' => fake()->numberBetween(500000000, 2000000000),
            'preferred_location' => fake()->city(),
            'requirements' => fake()->paragraphs(2, true),
            'last_contact_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'next_follow_up' => fake()->dateTimeBetween('now', '+1 month'),
            'conversion_date' => fake()->boolean(20) ? fake()->dateTimeBetween('-6 months', 'now') : null,
        ];
    }
}