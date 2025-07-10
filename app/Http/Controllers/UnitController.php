<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with(['project', 'unitType.project', 'project.location']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('unit_code', 'like', "%{$search}%")
                    ->orWhereHas('unitType', function ($ut) use ($search) {
                        $ut->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('project', function ($p) use ($search) {
                        $p->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by unit type
        if ($request->filled('unit_type_id')) {
            $query->where('unit_type_id', $request->unit_type_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $units = $query->paginate(12);

        // Get data for filters
        $projects = Project::orderBy('name')->get();
        $unitTypes = UnitType::orderBy('name')->get();

        // Calculate statistics
        $statistics = [
            'total_units' => Unit::count(),
            'available_units' => Unit::where('status', 'available')->count(),
            'booked_units' => Unit::where('status', 'booked')->count(),
            'sold_units' => Unit::where('status', 'sold')->count(),
        ];

        return view('units.index', compact('units', 'projects', 'unitTypes', 'statistics'));
    }

    public function show(Unit $unit)
    {
        $unit->load(['project.location', 'unitType', 'project.developer']);

        return view('units.show', compact('unit'));
    }

    public function create()
    {
        $projects = Project::with('location')->orderBy('name')->get();
        $unitTypes = UnitType::orderBy('name')->get();

        return view('units.create', compact('projects', 'unitTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'unit_type_id' => 'required|exists:unit_types,id',
            'unit_code' => 'required|string|unique:units,unit_code',
            'status' => 'required|in:available,booked,sold',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'facing' => 'nullable|string',
            'certificate' => 'nullable|string',
            'cash_hard_percentage' => 'nullable|numeric|min:0|max:100',
            'cash_tempo_percentage' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('units', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $unit = Unit::create($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unit created successfully.');
    }

    public function edit(Unit $unit)
    {
        $projects = Project::with('location')->orderBy('name')->get();
        $unitTypes = UnitType::orderBy('name')->get();

        return view('units.edit', compact('unit', 'projects', 'unitTypes'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'unit_type_id' => 'required|exists:unit_types,id',
            'unit_code' => 'required|string|unique:units,unit_code,' . $unit->id,
            'status' => 'required|in:available,booked,sold',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0',
            'facing' => 'nullable|string',
            'certificate' => 'nullable|string',
            'cash_hard_percentage' => 'nullable|numeric|min:0|max:100',
            'cash_tempo_percentage' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($unit->images) {
                foreach ($unit->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('units', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $unit->update($validated);

        return redirect()->route('units.index')
            ->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        // Check if unit has bookings
        if ($unit->bookings()->exists()) {
            return redirect()->route('units.index')
                ->with('error', 'Cannot delete unit with existing bookings.');
        }

        // Delete images
        if ($unit->images) {
            foreach ($unit->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit deleted successfully.');
    }

    public function updateStatus(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'status' => 'required|in:available,booked,sold'
        ]);

        $unit->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Unit status updated successfully.',
            'status' => $unit->status
        ]);
    }

    public function catalog(Request $request)
    {
        $query = Unit::with(['project.location', 'unitType'])
            ->where('status', 'available');

        // Search and filters for public catalog
        if ($request->filled('location')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('location_id', $request->location);
            });
        }

        if ($request->filled('price_range')) {
            $range = explode('-', $request->price_range);
            if (count($range) == 2) {
                $query->whereBetween('price', [$range[0], $range[1]]);
            }
        }

        if ($request->filled('bedrooms')) {
            $query->whereHas('unitType', function ($q) use ($request) {
                $q->where('bedrooms', '>=', $request->bedrooms);
            });
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('unit_type_id')) {
            $query->where('unit_type_id', $request->unit_type_id);
        }

        // Sorting
        switch ($request->get('sort', 'newest')) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'area_large':
                $query->join('unit_types', 'units.unit_type_id', '=', 'unit_types.id')
                    ->orderBy('unit_types.building_area', 'desc')
                    ->select('units.*');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $units = $query->paginate(12);

        // Get filter options
        $locations = \App\Models\Location::has('projects')->orderBy('city')->get();
        $projects = Project::where('status', 'ready')->orderBy('name')->get();
        $unitTypes = UnitType::has('units')->orderBy('name')->get();

        return view('catalog.index', compact('units', 'locations', 'projects', 'unitTypes'));
    }
}
