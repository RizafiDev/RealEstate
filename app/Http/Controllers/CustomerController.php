<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('id_number', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by customer type
        if ($request->filled('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        // Sort
        $sort = $request->get('sort', 'full_name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $customers = $query->paginate(10);

        // Statistics - Remove status references if column doesn't exist
        $stats = [
            'total' => Customer::count(),
            'with_bookings' => Customer::has('bookings')->count(),
            // Remove these if 'status' column doesn't exist
            // 'active' => Customer::where('status', 'active')->count(),
        ];

        return view('customers.index', compact('customers', 'stats'));
    }

    public function show(Customer $customer)
    {
        $customer->load(['bookings.unit.project']);
        return view('customers.show', compact('customer'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers',
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:50|unique:customers',
            'id_type' => 'nullable|in:ktp,passport,sim',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'occupation' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'customer_type' => 'required|in:individual,corporate',
            'company_full_name' => 'nullable|string|max:255',
            'emergency_contact_full_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $customer = Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'id_number' => 'nullable|string|max:50|unique:customers,id_number,' . $customer->id,
            'id_type' => 'nullable|in:ktp,passport,sim',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'occupation' => 'nullable|string|max:255',
            'monthly_income' => 'nullable|numeric|min:0',
            'customer_type' => 'required|in:individual,corporate',
            'company_full_name' => 'nullable|string|max:255',
            'emergency_contact_full_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        // Check if customer has bookings
        if ($customer->bookings()->count() > 0) {
            return redirect()->route('customers.index')
                ->with('error', 'Cannot delete customer that has associated bookings.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    public function export(Request $request)
    {
        $customers = Customer::all();

        $csvData = [];
        $csvData[] = [
            'full_name',
            'Email',
            'Phone',
            'ID Number',
            'Date of Birth',
            'Gender',
            'Address',
            'City',
            'Province',
            'Occupation',
            'Monthly Income',
            'Customer Type',
            'Company full_name',
            'Status',
            'Created At'
        ];

        foreach ($customers as $customer) {
            $csvData[] = [
                $customer->full_name,
                $customer->email,
                $customer->phone,
                $customer->id_number,
                $customer->date_of_birth?->format('Y-m-d'),
                $customer->gender,
                $customer->address,
                $customer->city,
                $customer->province,
                $customer->occupation,
                $customer->monthly_income,
                $customer->customer_type,
                $customer->company_full_name,
                $customer->status,
                $customer->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $filefull_name = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filefull_name="' . $filefull_name . '"',
        ];

        $callback = function () use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
