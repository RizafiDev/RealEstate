<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::with(['project']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Filter by project
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $campaigns = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Campaign::count(),
            'active' => Campaign::where('status', 'active')->count(),
            'completed' => Campaign::where('status', 'completed')->count(),
            'paused' => Campaign::where('status', 'paused')->count(),
        ];

        // For filters
        $projects = Project::all();

        return view('campaigns.index', compact('campaigns', 'stats', 'projects'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['project']);
        return view('campaigns.show', compact('campaign'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        return view('campaigns.create', compact('projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,paused,completed',
            'target_audience' => 'nullable|string',
            'channels' => 'nullable|array',
            'channels.*' => 'string',
            'objectives' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        $campaign = Campaign::create($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign created successfully.');
    }

    public function edit(Campaign $campaign)
    {
        $projects = Project::where('status', 'active')->get();
        return view('campaigns.edit', compact('campaign', 'projects'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,paused,completed',
            'target_audience' => 'nullable|string',
            'channels' => 'nullable|array',
            'channels.*' => 'string',
            'objectives' => 'nullable|string',
        ]);

        $campaign->update($validated);

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign updated successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        $campaign->delete();

        return redirect()->route('campaigns.index')
            ->with('success', 'Campaign deleted successfully.');
    }
}
