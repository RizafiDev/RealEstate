<?php

namespace App\Http\Controllers;

use App\Models\MarketingMaterial;
use App\Models\Project;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class MarketingMaterialController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketingMaterial::with(['project', 'campaign']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $materials = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => MarketingMaterial::count(),
            'brochures' => MarketingMaterial::where('type', 'brochure')->count(),
            'videos' => MarketingMaterial::where('type', 'video')->count(),
            'images' => MarketingMaterial::where('type', 'image')->count(),
        ];

        // For filters
        $projects = Project::all();
        $campaigns = Campaign::all();

        return view('marketing-materials.index', compact('materials', 'stats', 'projects', 'campaigns'));
    }

    public function show(MarketingMaterial $marketingMaterial)
    {
        $marketingMaterial->load(['project', 'campaign']);
        return view('marketing-materials.show', compact('marketingMaterial'));
    }

    public function create()
    {
        $projects = Project::all();
        $campaigns = Campaign::all();
        return view('marketing-materials.create', compact('projects', 'campaigns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:brochure,flyer,video,image,presentation,other',
            'project_id' => 'nullable|exists:projects,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'file_path' => 'required|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi,ppt,pptx|max:20480',
            'is_public' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('marketing-materials', 'public');
        }

        $validated['created_by'] = Auth::id();

        $material = MarketingMaterial::create($validated);

        return redirect()->route('marketing-materials.index')
            ->with('success', 'Marketing material created successfully.');
    }

    public function edit(MarketingMaterial $marketingMaterial)
    {
        $projects = Project::all();
        $campaigns = Campaign::all();
        return view('marketing-materials.edit', compact('marketingMaterial', 'projects', 'campaigns'));
    }

    public function update(Request $request, MarketingMaterial $marketingMaterial)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:brochure,flyer,video,image,presentation,other',
            'project_id' => 'nullable|exists:projects,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'file_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,pdf,mp4,mov,avi,ppt,pptx|max:20480',
            'is_public' => 'boolean',
        ]);

        // Handle file upload
        if ($request->hasFile('file_path')) {
            // Delete old file
            if ($marketingMaterial->file_path) {
                Storage::disk('public')->delete($marketingMaterial->file_path);
            }
            $validated['file_path'] = $request->file('file_path')->store('marketing-materials', 'public');
        }

        $marketingMaterial->update($validated);

        return redirect()->route('marketing-materials.index')
            ->with('success', 'Marketing material updated successfully.');
    }

    public function destroy(MarketingMaterial $marketingMaterial)
    {
        // Delete associated file
        if ($marketingMaterial->file_path) {
            Storage::disk('public')->delete($marketingMaterial->file_path);
        }

        $marketingMaterial->delete();

        return redirect()->route('marketing-materials.index')
            ->with('success', 'Marketing material deleted successfully.');
    }
}
