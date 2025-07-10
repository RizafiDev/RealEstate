<?php

namespace App\Http\Controllers;

use App\Models\Developer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeveloperController extends Controller
{
    public function index(Request $request)
    {
        $query = Developer::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        $query->orderBy($sort, $direction);

        $developers = $query->paginate(10);

        // Statistics
        $stats = [
            'total' => Developer::count(),
            'active' => Developer::where('status', 'active')->count(),
            'inactive' => Developer::where('status', 'inactive')->count(),
        ];

        return view('developers.index', compact('developers', 'stats'));
    }

    public function show(Developer $developer)
    {
        $developer->load(['projects']);
        return view('developers.show', compact('developer'));
    }

    public function create()
    {
        return view('developers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('developers/logos', 'public');
        }

        $developer = Developer::create($validated);

        return redirect()->route('developers.index')
            ->with('success', 'Developer created successfully.');
    }

    public function edit(Developer $developer)
    {
        return view('developers.edit', compact('developer'));
    }

    public function update(Request $request, Developer $developer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($developer->logo) {
                Storage::disk('public')->delete($developer->logo);
            }
            $validated['logo'] = $request->file('logo')->store('developers/logos', 'public');
        }

        $developer->update($validated);

        return redirect()->route('developers.index')
            ->with('success', 'Developer updated successfully.');
    }

    public function destroy(Developer $developer)
    {
        // Check if developer has projects
        if ($developer->projects()->count() > 0) {
            return redirect()->route('developers.index')
                ->with('error', 'Cannot delete developer that has associated projects.');
        }

        // Delete associated files
        if ($developer->logo) {
            Storage::disk('public')->delete($developer->logo);
        }

        $developer->delete();

        return redirect()->route('developers.index')
            ->with('success', 'Developer deleted successfully.');
    }
}
