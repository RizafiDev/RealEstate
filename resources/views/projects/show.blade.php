@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="space-y-6">
    <!-- Project Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="relative h-64">
            @if($project->master_plan)
            <img src="{{ asset('storage/' . $project->master_plan) }}" alt="{{ $project->name }}" class="h-full w-full object-cover">
            @else
            <div class="h-full w-full bg-gray-200 flex items-center justify-center">
                <i class="fas fa-building text-gray-400 text-6xl"></i>
            </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            <div class="absolute bottom-0 left-0 p-6">
                <h1 class="text-3xl font-bold text-white">{{ $project->name }}</h1>
                <p class="text-lg text-white/90">{{ $project->developer->name }}</p>
            </div>
            <span class="absolute top-4 right-4 px-3 py-1 text-sm font-semibold rounded-full 
                @if($project->status == 'completed') bg-green-100 text-green-800 
                @elseif($project->status == 'development') bg-blue-100 text-blue-800 
                @elseif($project->status == 'ready') bg-yellow-100 text-yellow-800 
                @else bg-gray-100 text-gray-800 @endif">
                {{ ucfirst($project->status) }}
            </span>
        </div>
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        <span>{{ $project->address }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        <span>Launch: {{ $project->launch_date?->format('M Y') ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <a href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        <i class="fas fa-plus mr-1"></i> Add Unit
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Project Description</h2>
                <div class="prose max-w-none text-gray-600">
                    {!! $project->description !!}
                </div>
            </div>

            <!-- Facilities -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Facilities</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($project->facilities as $facility)
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <i class="fas fa-check text-indigo-600"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $facility }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Gallery -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Gallery</h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($project->gallery as $image)
                    <div class="aspect-w-1 aspect-h-1">
                        <img src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $image->title }}" class="object-cover rounded-lg">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Stats -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Project Stats</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Total Units</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $project->total_units }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Available Units</p>
                        <p class="text-lg font-semibold text-gray-900">{{ $project->available_units }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Occupancy Rate</p>
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $project->occupancy_rate }}%"></div>
                            </div>
                            <span class="text-sm font-medium text-gray-700">{{ round($project->occupancy_rate) }}%</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Sales Value</p>
                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($project->total_sales_value, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Unit Types -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Unit Types</h2>
                <div class="space-y-4">
                    @foreach($project->unitTypes as $unitType)
                    <div class="border rounded-lg p-3">
                        <h3 class="font-medium text-gray-900">{{ $unitType->name }}</h3>
                        <div class="grid grid-cols-2 gap-2 mt-2 text-sm text-gray-600">
                            <div><i class="fas fa-ruler-combined mr-1"></i> {{ $unitType->building_area }} m²</div>
                            <div><i class="fas fa-bed mr-1"></i> {{ $unitType->bedrooms }} BR</div>
                            <div><i class="fas fa-bath mr-1"></i> {{ $unitType->bathrooms }} BA</div>
                            <div><i class="fas fa-car mr-1"></i> {{ $unitType->garages }} Garage</div>
                        </div>
                        <div class="mt-2 text-sm">
                            <span class="font-medium text-indigo-600">{{ $unitType->units_count }} Units</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Location Map -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Location</h2>
                <div class="aspect-w-16 aspect-h-9 bg-gray-200 rounded-lg overflow-hidden">
                    <!-- Map placeholder - would be replaced with actual map -->
                    <div class="h-full w-full flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-gray-400 text-4xl"></i>
                    </div>
                </div>
                <div class="mt-3 text-sm text-gray-600">
                    <p><i class="fas fa-map-marker-alt mr-2"></i> {{ $project->address }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Units Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden mt-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-lg font-medium text-gray-900">Units</h2>
            <div class="flex space-x-2">
                <button class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-download mr-1"></i> Export
                </button>
                <a href="{{ route('units.create', ['project_id' => $project->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    <i class="fas fa-plus mr-1"></i> Add Unit
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($project->units as $unit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $unit->block }} - {{ $unit->number }}</div>
                            <div class="text-sm text-gray-500">{{ $unit->unitType->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $unit->unitType->bedrooms }} BR / {{ $unit->unitType->bathrooms }} BA
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            Rp {{ number_format($unit->selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($unit->status == 'available') bg-green-100 text-green-800 
                                @elseif($unit->status == 'booked') bg-yellow-100 text-yellow-800 
                                @elseif($unit->status == 'sold') bg-indigo-100 text-indigo-800 
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($unit->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('units.show', $unit) }}" class="text-indigo-600 hover:text-indigo-900 mr-3"><i class="fas fa-eye"></i></a>
                            <a href="{{ route('units.edit', $unit) }}" class="text-yellow-600 hover:text-yellow-900 mr-3"><i class="fas fa-edit"></i></a>
                            <form action="{{ route('units.destroy', $unit) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $project->units->links() }}
        </div>
    </div>
</div>
@endsection