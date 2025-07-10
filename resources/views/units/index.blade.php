@extends('layouts.app')

@section('title', 'Units Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Units Management</h1>
        <a href="{{ route('units.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i> Add Unit
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-home text-blue-400 text-2xl"></i>
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

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Available</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['available_units'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Booked</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['booked_units'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-handshake text-purple-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sold</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['sold_units'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Unit code, project..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                <select id="project_id" name="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Projects</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}" @if(request('project_id') == $project->id) selected @endif>{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="unit_type_id" class="block text-sm font-medium text-gray-700">Unit Type</label>
                <select id="unit_type_id" name="unit_type_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Types</option>
                    @foreach($unitTypes as $type)
                        <option value="{{ $type->id }}" @if(request('unit_type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="available" @if(request('status') == 'available') selected @endif>Available</option>
                    <option value="booked" @if(request('status') == 'booked') selected @endif>Booked</option>
                    <option value="sold" @if(request('status') == 'sold') selected @endif>Sold</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('units.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Units Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($units as $unit)
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="relative">
                    @if($unit->images && count($unit->images) > 0)
                        <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $unit->images[0]) }}" alt="{{ $unit->unit_code }}">
                    @else
                        <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-home text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-2 right-2">
                        @if($unit->status == 'available')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Available
                            </span>
                        @elseif($unit->status == 'booked')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Booked
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Sold
                            </span>
                        @endif
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-lg font-medium text-gray-900 truncate">{{ $unit->unit_code }}</p>
                            <p class="text-sm text-gray-500">{{ $unit->unitType->name ?? 'No Type' }}</p>
                            <p class="text-sm text-indigo-600">{{ $unit->project->name ?? 'No Project' }}</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <p class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($unit->price, 0, ',', '.') }}
                        </p>
                        @if($unit->discount_price && $unit->discount_price < $unit->price)
                            <p class="text-sm text-red-600 line-through">
                                Rp {{ number_format($unit->price, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-expand-arrows-alt mr-2"></i>
                            <span>{{ $unit->unitType->building_area ?? 0 }}m²</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bed mr-2"></i>
                            <span>{{ $unit->unitType->bedrooms ?? 0 }} BR</span>
                        </div>
                    </div>

                    <div class="mt-6 flex space-x-2">
                        <a href="{{ route('units.show', $unit) }}" class="flex-1 bg-indigo-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View Details
                        </a>
                        <a href="{{ route('units.edit', $unit) }}" class="flex-1 bg-gray-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-home text-gray-400 text-6xl"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No units found</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding your first unit.</p>
                <div class="mt-6">
                    <a href="{{ route('units.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i> Add Unit
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($units->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $units->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
