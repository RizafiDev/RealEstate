@extends('layouts.app')

@section('title', 'Unit Types')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Unit Types</h1>
            <p class="text-gray-600">Manage property unit types</p>
        </div>
        <a href="{{ route('unit-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i> Add Unit Type
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-home text-gray-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Unit Types</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $unitTypes->total() ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bed text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Residential Types</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['residential'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-building text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Commercial Types</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['commercial'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line text-purple-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg. Area</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($stats['avg_area'] ?? 0, 0) }}m²</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search unit types..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                <select name="project_id" id="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Projects</option>
                    @foreach($projects ?? [] as $project)
                        <option value="{{ $project->id }}" @if(request('project_id') == $project->id) selected @endif>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Categories</option>
                    <option value="residential" @if(request('category') == 'residential') selected @endif>Residential</option>
                    <option value="commercial" @if(request('category') == 'commercial') selected @endif>Commercial</option>
                </select>
            </div>
            <div>
                <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                <select name="bedrooms" id="bedrooms" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">Any</option>
                    <option value="1" @if(request('bedrooms') == '1') selected @endif>1 BR</option>
                    <option value="2" @if(request('bedrooms') == '2') selected @endif>2 BR</option>
                    <option value="3" @if(request('bedrooms') == '3') selected @endif>3 BR</option>
                    <option value="4" @if(request('bedrooms') == '4') selected @endif>4+ BR</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('unit-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Unit Types Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($unitTypes ?? [] as $unitType)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                @if($unitType->floor_plan)
                    <div class="aspect-w-16 aspect-h-9">
                        <img src="{{ Storage::url($unitType->floor_plan) }}" alt="{{ $unitType->name }}" class="w-full h-48 object-cover">
                    </div>
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <i class="fas fa-home text-gray-400 text-4xl"></i>
                    </div>
                @endif

                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-lg font-medium text-gray-900">{{ $unitType->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ ucfirst($unitType->category ?? 'residential') }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-4">{{ $unitType->project->name ?? 'No Project' }}</p>

                    @if($unitType->description)
                        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $unitType->description }}</p>
                    @endif

                    <!-- Specifications -->
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $unitType->bedrooms ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Bedrooms</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ $unitType->bathrooms ?? 0 }}</div>
                            <div class="text-xs text-gray-500">Bathrooms</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ number_format($unitType->building_area ?? 0, 0) }}</div>
                            <div class="text-xs text-gray-500">Building (m²)</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-semibold text-gray-900">{{ number_format($unitType->land_area ?? 0, 0) }}</div>
                            <div class="text-xs text-gray-500">Land (m²)</div>
                        </div>
                    </div>

                    <!-- Features -->
                    @if($unitType->features)
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-1">
                                @php
                                    $featuresArray = is_array($unitType->features) ? $unitType->features : explode(',', $unitType->features);
                                    $featuresArray = array_filter(array_map('trim', $featuresArray));
                                @endphp
                                @foreach(array_slice($featuresArray, 0, 3) as $feature)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $feature }}
                                    </span>
                                @endforeach
                                @if(count($featuresArray) > 3)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        +{{ count($featuresArray) - 3 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Units Count -->
                    <div class="mb-4 text-sm text-gray-600">
                        <i class="fas fa-home mr-1"></i>
                        {{ $unitType->units_count ?? 0 }} units available
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <a href="{{ route('unit-types.show', $unitType) }}" class="flex-1 bg-gray-100 text-gray-700 text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-eye mr-1"></i> View
                        </a>
                        <a href="{{ route('unit-types.edit', $unitType) }}" class="flex-1 bg-indigo-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-home text-gray-400 text-6xl"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No unit types</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating a new unit type.</p>
                <div class="mt-6">
                    <a href="{{ route('unit-types.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i> Add Unit Type
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if(isset($unitTypes) && $unitTypes->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $unitTypes->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection