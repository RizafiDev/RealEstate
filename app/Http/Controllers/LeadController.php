<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::with(['project', 'assignedTo']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
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

        // Filter by source
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        // Filter by assigned user
        if ($request->filled('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }

        // Sort
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        $query->orderBy($sort, $direction);

        $leads = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Lead::count(),
            'new' => Lead::where('status', 'new')->count(),
            'contacted' => Lead::where('status', 'contacted')->count(),
            'qualified' => Lead::where('status', 'qualified')->count(),
            'converted' => Lead::where('status', 'converted')->count(),
        ];

        // For filters
        $projects = Project::all();
        $agents = User::all(); // Add this missing variable

        return view('leads.index', compact('leads', 'stats', 'projects', 'agents'));
    }

    public function show(Lead $lead)
    {
        $lead->load(['project', 'assignedTo', 'activities']);
        return view('leads.show', compact('lead'));
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        $users = User::all();

        return view('leads.create', compact('projects', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'project_id' => 'nullable|exists:projects,id',
            'source' => 'required|in:website,referral,walk_in,social_media,advertisement,other',
            'status' => 'required|in:new,contacted,qualified,converted,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'preferred_contact' => 'required|in:phone,email,whatsapp',
        ]);

        $validated['created_by'] = Auth::id();

        $lead = Lead::create($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function edit(Lead $lead)
    {
        $projects = Project::where('status', 'active')->get();
        $users = User::all();

        return view('leads.edit', compact('lead', 'projects', 'users'));
    }

    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'project_id' => 'nullable|exists:projects,id',
            'source' => 'required|in:website,referral,walk_in,social_media,advertisement,other',
            'status' => 'required|in:new,contacted,qualified,converted,lost',
            'assigned_to' => 'nullable|exists:users,id',
            'budget_min' => 'nullable|numeric|min:0',
            'budget_max' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'preferred_contact' => 'required|in:phone,email,whatsapp',
        ]);

        // Track status changes for activity log
        if ($lead->status !== $validated['status']) {
            $lead->activities()->create([
                'type' => 'status_change',
                'description' => "Status changed from {$lead->status} to {$validated['status']}",
                'user_id' => Auth::id(),
            ]);
        }

        $lead->update($validated);

        return redirect()->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead)
    {
        $lead->delete();

        return redirect()->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $data = array_map('str_getcsv', file($file->getRealPath()));
        $header = array_shift($data);

        $imported = 0;
        foreach ($data as $row) {
            $row = array_combine($header, $row);

            try {
                Lead::create([
                    'name' => $row['name'] ?? '',
                    'email' => $row['email'] ?? null,
                    'phone' => $row['phone'] ?? '',
                    'source' => $row['source'] ?? 'other',
                    'status' => 'new',
                    'created_by' => Auth::id(),
                    'notes' => $row['notes'] ?? null,
                ]);
                $imported++;
            } catch (\Exception $e) {
                // Skip invalid rows
                continue;
            }
        }

        return redirect()->route('leads.index')
            ->with('success', "Successfully imported {$imported} leads.");
    }
}
