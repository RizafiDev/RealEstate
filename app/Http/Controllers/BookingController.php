<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Unit;
use App\Models\Customer;
use App\Models\User;
use App\Models\Project; // Add this missing model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['unit.project', 'customer', 'salesAgent']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('customer', function ($subQ) use ($search) {
                        $subQ->where('name', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('unit', function ($subQ) use ($search) {
                        $subQ->where('unit_number', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter by sales person
        if ($request->filled('sales_person_id')) {
            $query->where('sales_person_id', $request->sales_person_id);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $bookings = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Booking::count(),
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'dp_paid' => Booking::where('status', 'dp_paid')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
        ];

        // For filters
        $units = Unit::with('project')->get();
        $salesPersons = User::all();
        $projects = Project::all(); // Add this missing variable

        return view('bookings.index', compact('bookings', 'stats', 'units', 'salesPersons', 'projects'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['unit.project', 'customer', 'salesAgent']);
        return view('bookings.show', compact('booking'));
    }

    public function create()
    {
        $units = Unit::where('status', 'available')->with('project')->get();
        $customers = Customer::all();
        $salesPersons = User::all();

        return view('bookings.create', compact('units', 'customers', 'salesPersons'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'customer_id' => 'required|exists:customers,id',
            'sales_person_id' => 'required|exists:users,id',
            'booking_date' => 'required|date',
            'booking_fee' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,kpr',
            'notes' => 'nullable|string',
        ]);

        // Generate booking number
        $validated['booking_number'] = 'BK-' . date('Ymd') . '-' . str_pad(Booking::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'pending';

        $booking = Booking::create($validated);

        // Update unit status to booked
        $booking->unit->update(['status' => 'booked']);

        return redirect()->route('bookings.index')
            ->with('success', 'Booking created successfully.');
    }

    public function edit(Booking $booking)
    {
        $units = Unit::where('status', 'available')
            ->orWhere('id', $booking->unit_id)
            ->with('project')->get();
        $customers = Customer::all();
        $salesPersons = User::all();

        return view('bookings.edit', compact('booking', 'units', 'customers', 'salesPersons'));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'customer_id' => 'required|exists:customers,id',
            'sales_person_id' => 'required|exists:users,id',
            'booking_date' => 'required|date',
            'booking_fee' => 'required|numeric|min:0',
            'dp_amount' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,transfer,kpr',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,dp_paid,completed,cancelled',
        ]);

        $oldUnitId = $booking->unit_id;
        $booking->update($validated);

        // Update unit status if unit changed
        if ($oldUnitId !== $validated['unit_id']) {
            // Release old unit
            Unit::where('id', $oldUnitId)->update(['status' => 'available']);
            // Book new unit
            Unit::where('id', $validated['unit_id'])->update(['status' => 'booked']);
        }

        return redirect()->route('bookings.index')
            ->with('success', 'Booking updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        // Release the unit
        $booking->unit->update(['status' => 'available']);

        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }

    public function confirm(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking confirmed successfully.');
    }

    public function markDpPaid(Booking $booking)
    {
        $booking->update([
            'status' => 'dp_paid',
            'dp_paid_at' => now(),
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'DP payment marked as paid.');
    }

    public function contract(Booking $booking)
    {
        $booking->load(['unit.project', 'customer', 'salesAgent']);

        return view('bookings.contract', compact('booking'));
    }
}
