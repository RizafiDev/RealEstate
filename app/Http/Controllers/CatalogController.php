<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Project;
use App\Models\Location;
use App\Models\UnitType;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Unit::with(['project.location', 'unitType'])
            ->where('status', 'available');

        // Filter by location
        if ($request->filled('location')) {
            $query->whereHas('project', function ($q) use ($request) {
                $q->where('location_id', $request->location);
            });
        }

        // Filter by price range
        if ($request->filled('price_range')) {
            $priceRange = explode('-', $request->price_range);
            if (count($priceRange) == 2) {
                $query->whereBetween('price', [$priceRange[0], $priceRange[1]]);
            }
        }

        // Filter by bedrooms
        if ($request->filled('bedrooms')) {
            $bedrooms = $request->bedrooms;
            $query->whereHas('unitType', function ($q) use ($bedrooms) {
                if ($bedrooms == '4') {
                    $q->where('bedrooms', '>=', 4);
                } else {
                    $q->where('bedrooms', $bedrooms);
                }
            });
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('unit_code', 'LIKE', "%{$search}%")
                    ->orWhereHas('project', function ($subQ) use ($search) {
                        $subQ->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('unitType', function ($subQ) use ($search) {
                        $subQ->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Sort
        $sort = $request->get('sort', 'price');
        $direction = $request->get('direction', 'asc');

        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('price', 'asc');
        }

        $units = $query->paginate(12);

        // For filters
        $locations = Location::all();
        $projects = Project::with('location')->where('status', 'active')->get();
        $unitTypes = UnitType::all();

        // Statistics
        $statistics = [
            'total_units' => Unit::where('status', 'available')->count(),
            'total_projects' => Project::where('status', 'active')->count(),
            'price_range' => [
                'min' => Unit::where('status', 'available')->min('price'),
                'max' => Unit::where('status', 'available')->max('price'),
            ]
        ];

        return view('catalog.index', compact('units', 'locations', 'projects', 'unitTypes', 'statistics'));
    }

    public function show(Unit $unit)
    {
        // Only show available units in catalog
        if ($unit->status !== 'available') {
            abort(404);
        }

        $unit->load(['project.location', 'project.developer', 'unitType']);

        // Get similar units
        $similarUnits = Unit::with(['project.location', 'unitType'])
            ->where('status', 'available')
            ->where('id', '!=', $unit->id)
            ->where('project_id', $unit->project_id)
            ->limit(4)
            ->get();

        return view('catalog.show', compact('unit', 'similarUnits'));
    }

    public function project(Project $project)
    {
        $project->load(['location', 'developer']);

        $units = Unit::with(['unitType'])
            ->where('project_id', $project->id)
            ->where('status', 'available')
            ->paginate(12);

        return view('catalog.project', compact('project', 'units'));
    }
}
