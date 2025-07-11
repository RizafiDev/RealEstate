<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Developer;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['developer', 'location', 'units']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('slug', 'LIKE', "%{$search}%");
            });
        }

        // Filter by developer
        if ($request->filled('developer_id')) {
            $query->where('developer_id', $request->developer_id);
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $projects = $query->paginate(12);

        // Calculate statistics
        $statistics = [
            'total_projects' => Project::count(),
            'ready_projects' => Project::where('status', 'ready')->count(),
            'development_projects' => Project::where('status', 'development')->count(),
            'total_units' => \App\Models\Unit::count(),
            'completed_projects' => Project::where('status', 'completed')->count(),
            'planning_projects' => Project::where('status', 'planning')->count(),
        ];

        // Get data for filters
        $developers = \App\Models\Developer::orderBy('name')->get();

        return view('projects.index', compact('projects', 'statistics', 'developers'));
    }

    public function show(Project $project)
    {
        $project->load(['developer', 'location', 'units']);
        return view('projects.show', compact('project'));
    }

    public function create()
    {
        $developers = Developer::all();
        $locations = Location::all();

        return view('projects.create', compact('developers', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'developer_id' => 'required|exists:developers,id',
            'location_id' => 'required|exists:locations,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'status' => 'required|in:planning,development,ready,completed',
            'start_date' => 'nullable|date',
            'estimated_completion' => 'nullable|date',
            'total_units' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:20',
            'sales_phone' => 'nullable|string|max:20',
            'sales_email' => 'nullable|email',
            'facilities' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('projects', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        $project = Project::create($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $project)
    {
        $developers = Developer::all();
        $locations = Location::all();

        return view('projects.edit', compact('project', 'developers', 'locations'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:projects,slug,' . $project->id,
            'description' => 'nullable|string',
            'developer_id' => 'required|exists:developers,id',
            'location_id' => 'required|exists:locations,id',
            'total_units' => 'required|integer|min:1',
            'launch_date' => 'nullable|date',
            'completion_date' => 'nullable|date',
            'status' => 'required|in:planned,active,completed,on_hold',
            'price_start' => 'nullable|numeric|min:0',
            'price_end' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'brochure' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        // Handle images upload
        if ($request->hasFile('images')) {
            // Delete old images
            $oldImages = $project->images ?? [];
            foreach ($oldImages as $oldImage) {
                Storage::disk('public')->delete($oldImage);
            }

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('projects/images', 'public');
                $imagePaths[] = $path;
            }
            $validated['images'] = $imagePaths;
        }

        // Handle brochure upload
        if ($request->hasFile('brochure')) {
            // Delete old brochure
            if ($project->brochure) {
                Storage::disk('public')->delete($project->brochure);
            }
            $validated['brochure'] = $request->file('brochure')->store('projects/brochures', 'public');
        }

        $project->update($validated);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        // Delete associated files
        $images = $project->images ?? [];
        foreach ($images as $image) {
            Storage::disk('public')->delete($image);
        }

        if ($project->brochure) {
            Storage::disk('public')->delete($project->brochure);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
