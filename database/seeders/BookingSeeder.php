<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $units = Unit::where('status', 'available')->take(50)->get();
        $users = User::all();

        Booking::factory(50)->create()->each(function ($booking) use ($customers, $units, $users) {
            $unit = $units->random();
            $booking->update([
                'customer_id' => $customers->random()->id,
                'unit_id' => $unit->id,
                'sales_agent_id' => $users->random()->id,
                'unit_price' => $unit->price,
                'total_price' => $unit->price - $booking->discount_amount,
            ]);

            // Update unit status
            $unit->update(['status' => 'booked']);
        });
    }
}