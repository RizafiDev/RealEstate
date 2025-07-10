<?php

namespace App\Http\Controllers;

use App\Models\UnitType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UnitTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = UnitType::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $unitTypes = $query->paginate(10);

        // Statistics - Remove status references
        $stats = [
            'total' => UnitType::count(),
            // Remove these lines as 'status' column doesn't exist
            // 'active' => UnitType::where('status', 'active')->count(),
            // 'inactive' => UnitType::where('status', 'inactive')->count(),
        ];

        return view('unit-types.index', compact('unitTypes', 'stats'));
    }

    public function show(UnitType $unitType)
    {
        $unitType->load(['units']);
        return view('unit-types.show', compact('unitType'));
    }

    public function create()
    {
        return view('unit-types.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'building_area' => 'required|numeric|min:0',
            'land_area' => 'nullable|numeric|min:0',
            'floor_plan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle floor plan upload
        if ($request->hasFile('floor_plan')) {
            $validated['floor_plan'] = $request->file('floor_plan')->store('unit-types/floor-plans', 'public');
        }

        // Convert features array to JSON
        if (isset($validated['features'])) {
            $validated['features'] = json_encode($validated['features']);
        }

        $unitType = UnitType::create($validated);

        return redirect()->route('unit-types.index')
            ->with('success', 'Unit Type created successfully.');
    }

    public function edit(UnitType $unitType)
    {
        return view('unit-types.edit', compact('unitType'));
    }

    public function update(Request $request, UnitType $unitType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'building_area' => 'required|numeric|min:0',
            'land_area' => 'nullable|numeric|min:0',
            'floor_plan' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle floor plan upload
        if ($request->hasFile('floor_plan')) {
            // Delete old floor plan
            if ($unitType->floor_plan) {
                Storage::disk('public')->delete($unitType->floor_plan);
            }
            $validated['floor_plan'] = $request->file('floor_plan')->store('unit-types/floor-plans', 'public');
        }

        // Convert features array to JSON
        if (isset($validated['features'])) {
            $validated['features'] = json_encode($validated['features']);
        }

        $unitType->update($validated);

        return redirect()->route('unit-types.index')
            ->with('success', 'Unit Type updated successfully.');
    }

    public function destroy(UnitType $unitType)
    {
        // Check if unit type has units
        if ($unitType->units()->count() > 0) {
            return redirect()->route('unit-types.index')
                ->with('error', 'Cannot delete unit type that has associated units.');
        }

        // Delete associated files
        if ($unitType->floor_plan) {
            Storage::disk('public')->delete($unitType->floor_plan);
        }

        $unitType->delete();

        return redirect()->route('unit-types.index')
            ->with('success', 'Unit Type deleted successfully.');
    }
}
