@extends('layouts.app')

@section('title', 'Projects')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Projects</h1>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i> Add Project
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-building text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Projects</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_projects'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Ready Projects</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['ready_projects'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-hammer text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">In Development</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['development_projects'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-home text-indigo-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Units</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_units'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="planning" @if(request('status') == 'planning') selected @endif>Planning</option>
                    <option value="development" @if(request('status') == 'development') selected @endif>Development</option>
                    <option value="ready" @if(request('status') == 'ready') selected @endif>Ready</option>
                    <option value="completed" @if(request('status') == 'completed') selected @endif>Completed</option>
                </select>
            </div>
            <div>
                <label for="developer" class="block text-sm font-medium text-gray-700">Developer</label>
                <select id="developer" name="developer" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Developers</option>
                    @foreach($developers as $developer)
                        <option value="{{ $developer->id }}" @if(request('developer') == $developer->id) selected @endif>{{ $developer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('projects.index') }}" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($projects as $project)
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="relative pb-3/4 h-48">
                @if($project->master_plan)
                <img src="{{ asset('storage/' . $project->master_plan) }}" alt="{{ $project->name }}" class="absolute h-full w-full object-cover">
                @else
                <div class="absolute h-full w-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-building text-gray-400 text-4xl"></i>
                </div>
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-4">
                    <h3 class="text-lg font-bold text-white">{{ $project->name }}</h3>
                    <p class="text-sm text-white/90">{{ $project->developer->name }}</p>
                </div>
                <span class="absolute top-2 right-2 px-2 py-1 text-xs font-semibold rounded-full 
                    @if($project->status == 'completed') bg-green-100 text-green-800 
                    @elseif($project->status == 'development') bg-blue-100 text-blue-800 
                    @elseif($project->status == 'ready') bg-yellow-100 text-yellow-800 
                    @else bg-gray-100 text-gray-800 @endif">
                    {{ ucfirst($project->status) }}
                </span>
            </div>
            <div class="p-4">
                <div class="flex justify-between items-center mb-2">
                    <div>
                        <span class="text-sm text-gray-600"><i class="fas fa-map-marker-alt mr-1"></i> {{ $project->location->name }}</span>
                    </div>
                    <div class="text-sm font-semibold text-indigo-600">
                        {{ $project->units_count }} Units
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mb-3">
                    <div>
                        <i class="fas fa-calendar-alt mr-1"></i> Launch: {{ $project->launch_date?->format('M Y') ?? 'N/A' }}
                    </div>
                    <div>
                        <i class="fas fa-calendar-check mr-1"></i> Completion: {{ $project->completion_date?->format('M Y') ?? 'N/A' }}
                    </div>
                </div>
                <div class="h-2 bg-gray-200 rounded-full overflow-hidden mb-3">
                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ $project->occupancy_rate }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mb-4">
                    <span>Occupancy: {{ round($project->occupancy_rate) }}%</span>
                    <span>Available: {{ $project->available_units }}</span>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('projects.show', $project) }}" class="flex-1 inline-flex justify-center items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-eye mr-1"></i> View
                    </a>
                    <a href="{{ route('projects.edit', $project) }}" class="flex-1 inline-flex justify-center items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $projects->links() }}
    </div>
</div>
@endsection