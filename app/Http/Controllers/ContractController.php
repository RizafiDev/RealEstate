<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with(['booking.unit.project', 'booking.customer']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('contract_number', 'LIKE', "%{$search}%")
                    ->orWhereHas('booking.customer', function ($subQ) use ($search) {
                        $subQ->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $contracts = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Contract::count(),
            'draft' => Contract::where('status', 'draft')->count(),
            'active' => Contract::where('status', 'active')->count(),
            'completed' => Contract::where('status', 'completed')->count(),
            'cancelled' => Contract::where('status', 'cancelled')->count(),
        ];

        return view('contracts.index', compact('contracts', 'stats'));
    }

    public function show(Contract $contract)
    {
        $contract->load(['booking.unit.project', 'booking.customer']);
        return view('contracts.show', compact('contract'));
    }

    public function create()
    {
        $bookings = Booking::with(['unit.project', 'customer'])
            ->where('status', 'dp_paid')
            ->whereDoesntHave('contract')
            ->get();

        return view('contracts.create', compact('bookings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'contract_date' => 'required|date',
            'terms_conditions' => 'required|string',
            'payment_schedule' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'contract_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Generate contract number
        $validated['contract_number'] = 'CNT-' . date('Ymd') . '-' . str_pad(Contract::count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['status'] = 'draft';
        $validated['created_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        $contract = Contract::create($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    public function edit(Contract $contract)
    {
        $bookings = Booking::with(['unit.project', 'customer'])
            ->where('status', 'dp_paid')
            ->where(function ($q) use ($contract) {
                $q->whereDoesntHave('contract')
                    ->orWhere('id', $contract->booking_id);
            })
            ->get();

        return view('contracts.edit', compact('contract', 'bookings'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'contract_date' => 'required|date',
            'terms_conditions' => 'required|string',
            'payment_schedule' => 'nullable|string',
            'special_conditions' => 'nullable|string',
            'status' => 'required|in:draft,active,completed,cancelled',
            'contract_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('contract_file')) {
            // Delete old file
            if ($contract->contract_file) {
                Storage::disk('public')->delete($contract->contract_file);
            }
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'public');
        }

        $contract->update($validated);

        // Update booking status if contract is activated
        if ($validated['status'] === 'active' && $contract->status !== 'active') {
            $contract->booking->update(['status' => 'completed']);
            $contract->booking->unit->update(['status' => 'sold']);
        }

        return redirect()->route('contracts.index')
            ->with('success', 'Contract updated successfully.');
    }

    public function destroy(Contract $contract)
    {
        // Delete associated file
        if ($contract->contract_file) {
            Storage::disk('public')->delete($contract->contract_file);
        }

        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Contract deleted successfully.');
    }
}
