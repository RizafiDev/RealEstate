@extends('layouts.app')

@section('title', 'Digital Catalog')

@section('content')
<div class="space-y-6">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-blue-600 rounded-lg shadow-lg overflow-hidden">
        <div class="absolute inset-0 bg-black opacity-20"></div>
        <div class="relative px-6 py-12 sm:px-12">
            <div class="max-w-3xl">
                <h1 class="text-4xl font-bold text-white mb-4">Find Your Dream Home</h1>
                <p class="text-xl text-indigo-100 mb-8">Discover premium residential properties with modern amenities and strategic locations.</p>
                
                <!-- Quick Search -->
                <div class="bg-white rounded-lg p-4 shadow-lg">
                    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <select name="location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select Location</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" @if(request('location') == $location->id) selected @endif>
                                        {{ $location->city }}, {{ $location->province }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <select name="price_range" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Price Range</option>
                                <option value="0-500000000" @if(request('price_range') == '0-500000000') selected @endif>< Rp 500M</option>
                                <option value="500000000-1000000000" @if(request('price_range') == '500000000-1000000000') selected @endif>Rp 500M - 1B</option>
                                <option value="1000000000-2000000000" @if(request('price_range') == '1000000000-2000000000') selected @endif>Rp 1B - 2B</option>
                                <option value="2000000000-999999999999" @if(request('price_range') == '2000000000-999999999999') selected @endif>> Rp 2B</option>
                            </select>
                        </div>
                        <div>
                            <select name="bedrooms" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Bedrooms</option>
                                <option value="1" @if(request('bedrooms') == '1') selected @endif>1 Bedroom</option>
                                <option value="2" @if(request('bedrooms') == '2') selected @endif>2 Bedrooms</option>
                                <option value="3" @if(request('bedrooms') == '3') selected @endif>3 Bedrooms</option>
                                <option value="4" @if(request('bedrooms') == '4') selected @endif>4+ Bedrooms</option>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-search mr-2"></i> Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Sort -->
    <div class="bg-white shadow rounded-lg p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-700">{{ $units->total() }} properties found</span>
                
                <!-- View Toggle -->
                <div class="flex bg-gray-100 rounded-md p-1">
                    <button onclick="setView('grid')" id="gridView" class="px-3 py-1 text-sm font-medium rounded-md transition-colors bg-white text-gray-900 shadow-sm">
                        <i class="fas fa-th mr-1"></i> Grid
                    </button>
                    <button onclick="setView('list')" id="listView" class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-900">
                        <i class="fas fa-list mr-1"></i> List
                    </button>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <!-- Sort -->
                <select onchange="location.href=this.value" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" @if(request('sort') == 'newest') selected @endif>Newest First</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}" @if(request('sort') == 'price_low') selected @endif>Price: Low to High</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}" @if(request('sort') == 'price_high') selected @endif>Price: High to Low</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'area_large']) }}" @if(request('sort') == 'area_large') selected @endif>Largest Area</option>
                </select>
                
                <!-- Advanced Filter Toggle -->
                <button onclick="toggleAdvancedFilters()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sliders-h mr-2"></i> Filters
                </button>
            </div>
        </div>

        <!-- Advanced Filters (Hidden by default) -->
        <div id="advancedFilters" class="hidden mt-4 pt-4 border-t border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-6 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Project</label>
                    <select name="project_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" @if(request('project_id') == $project->id) selected @endif>{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Unit Type</label>
                    <select name="unit_type_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Types</option>
                        @foreach($unitTypes as $type)
                            <option value="{{ $type->id }}" @if(request('unit_type_id') == $type->id) selected @endif>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Min Area (m²)</label>
                    <input type="number" name="min_area" value="{{ request('min_area') }}" placeholder="e.g. 50" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Max Area (m²)</label>
                    <input type="number" name="max_area" value="{{ request('max_area') }}" placeholder="e.g. 200" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Facilities</label>
                    <select name="facilities" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">Any</option>
                        <option value="swimming_pool" @if(request('facilities') == 'swimming_pool') selected @endif>Swimming Pool</option>
                        <option value="gym" @if(request('facilities') == 'gym') selected @endif>Gym</option>
                        <option value="playground" @if(request('facilities') == 'playground') selected @endif>Playground</option>
                        <option value="security" @if(request('facilities') == 'security') selected @endif>24h Security</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                        Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Units Grid -->
    <div id="unitsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($units as $unit)
            <div class="unit-card bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 cursor-pointer" onclick="viewUnit({{ $unit->id }})">
                <!-- Image -->
                <div class="relative h-48 bg-gray-200">
                    @if($unit->images && count($unit->images) > 0)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $unit->images[0]) }}" alt="{{ $unit->unit_code }}">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-home text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Available
                        </span>
                    </div>
                    
                    <!-- Wishlist -->
                    <button onclick="event.stopPropagation(); toggleWishlist({{ $unit->id }})" class="absolute top-3 right-3 p-2 rounded-full bg-white bg-opacity-90 hover:bg-opacity-100 transition-all">
                        <i class="far fa-heart text-gray-600 hover:text-red-500"></i>
                    </button>
                    
                    <!-- Image Count -->
                    @if($unit->images && count($unit->images) > 1)
                    <div class="absolute bottom-3 right-3 bg-black bg-opacity-50 text-white px-2 py-1 rounded text-xs">
                        <i class="fas fa-camera mr-1"></i> {{ count($unit->images) }}
                    </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Project & Unit Info -->
                    <div class="mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $unit->unit_code }}</h3>
                        <p class="text-sm text-indigo-600 mb-1">{{ $unit->project->name }}</p>
                        <p class="text-xs text-gray-500">{{ $unit->project->location->city }}, {{ $unit->project->location->province }}</p>
                    </div>

                    <!-- Specifications -->
                    <div class="grid grid-cols-3 gap-2 mb-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-expand-arrows-alt mr-1 text-xs"></i>
                            <span>{{ $unit->unitType->building_area }}m²</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bed mr-1 text-xs"></i>
                            <span>{{ $unit->unitType->bedrooms }} BR</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-bath mr-1 text-xs"></i>
                            <span>{{ $unit->unitType->bathrooms }} BA</span>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <div class="text-2xl font-bold text-gray-900">
                            Rp {{ number_format($unit->price, 0, ',', '.') }}
                        </div>
                        <div class="text-sm text-gray-500">
                            Rp {{ number_format($unit->price / $unit->unitType->building_area, 0, ',', '.') }}/m²
                        </div>
                    </div>

                    <!-- Features -->
                    @if($unit->unitType->features)
                    <div class="mb-4">
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice(explode(',', $unit->unitType->features), 0, 3) as $feature)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ trim($feature) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex space-x-2">
                        <button onclick="event.stopPropagation(); viewUnit({{ $unit->id }})" class="flex-1 bg-indigo-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            View Details
                        </button>
                        <button onclick="event.stopPropagation(); contactSales({{ $unit->id }})" class="flex-1 bg-gray-600 text-white text-center px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Contact Sales
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No properties found</h3>
                <p class="text-gray-500 mb-6">Try adjusting your search criteria or browse all available properties.</p>
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    View All Properties
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($units->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow">
            {{ $units->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Floating Compare Button -->
<div id="compareButton" class="fixed bottom-6 right-6 hidden">
    <button onclick="showComparison()" class="bg-purple-600 text-white px-6 py-3 rounded-full shadow-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
        <i class="fas fa-balance-scale mr-2"></i>
        Compare (<span id="compareCount">0</span>)
    </button>
</div>

@push('scripts')
<script>
let compareList = [];
let currentView = 'grid';

function setView(view) {
    currentView = view;
    const gridBtn = document.getElementById('gridView');
    const listBtn = document.getElementById('listView');
    const container = document.getElementById('unitsContainer');
    
    if (view === 'grid') {
        gridBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
        gridBtn.classList.remove('text-gray-600');
        listBtn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
        listBtn.classList.add('text-gray-600');
        container.classList.remove('space-y-4');
        container.classList.add('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-6');
    } else {
        listBtn.classList.add('bg-white', 'text-gray-900', 'shadow-sm');
        listBtn.classList.remove('text-gray-600');
        gridBtn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm');
        gridBtn.classList.add('text-gray-600');
        container.classList.add('space-y-4');
        container.classList.remove('grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-6');
    }
}

function toggleAdvancedFilters() {
    const filters = document.getElementById('advancedFilters');
    filters.classList.toggle('hidden');
}

function viewUnit(unitId) {
    window.location.href = `/catalog/units/${unitId}`;
}

function toggleWishlist(unitId) {
    // Implementation for wishlist functionality
    console.log('Toggle wishlist for unit:', unitId);
    // This would typically make an AJAX request to add/remove from wishlist
}

function contactSales(unitId) {
    // Implementation for contacting sales
    window.location.href = `/contact/sales?unit_id=${unitId}`;
}

function showComparison() {
    // Implementation for showing comparison modal
    console.log('Show comparison for units:', compareList);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    setView('grid');
});
</script>
@endpush
@endsection
