@extends('layouts.app')

@section('title', $project->name . ' - Project Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Breadcrumb -->
    <nav class="flex mb-8" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                    <i class="fas fa-home mr-2"></i>
                    Catalog
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $project->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Project Header -->
    <div class="bg-white shadow rounded-lg overflow-hidden mb-8">
        <div class="relative h-64 bg-gradient-to-r from-indigo-600 to-purple-600">
            @if($project->images && count($project->images) > 0)
                <img src="{{ asset('storage/' . $project->images[0]) }}" alt="{{ $project->name }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black opacity-40"></div>
            @endif
            <div class="absolute inset-0 flex items-end">
                <div class="p-6 text-white">
                    <h1 class="text-4xl font-bold mb-2">{{ $project->name }}</h1>
                    <p class="text-xl opacity-90">by {{ $project->developer->name ?? 'Developer' }}</p>
                    <p class="text-lg opacity-75 flex items-center mt-2">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        {{ $project->location->name ?? '' }}, {{ $project->location->city ?? '' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <!-- Project Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600">{{ $project->total_units ?? 0 }}</div>
                    <div class="text-sm text-gray-500">Total Units</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600">{{ $units->total() }}</div>
                    <div class="text-sm text-gray-500">Available Units</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">
                        @if($project->price_start)
                            Rp {{ number_format($project->price_start / 1000000, 0, ',', '.') }}M
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">Starting Price</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-purple-600">
                        {{ $project->status ? ucfirst($project->status) : 'Active' }}
                    </div>
                    <div class="text-sm text-gray-500">Project Status</div>
                </div>
            </div>

            <!-- Project Description -->
            @if($project->description)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">About This Project</h3>
                    <p class="text-gray-600">{{ $project->description }}</p>
                </div>
            @endif

            <!-- Facilities -->
            @if($project->facilities && count($project->facilities) > 0)
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Facilities</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        @foreach($project->facilities as $facility)
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                {{ $facility }}
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Contact Info -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($project->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-indigo-600 mr-3"></i>
                            <span class="text-gray-600">{{ $project->phone }}</span>
                        </div>
                    @endif
                    @if($project->sales_phone)
                        <div class="flex items-center">
                            <i class="fas fa-mobile-alt text-indigo-600 mr-3"></i>
                            <span class="text-gray-600">{{ $project->sales_phone }}</span>
                        </div>
                    @endif
                    @if($project->sales_email)
                        <div class="flex items-center">
                            <i class="fas fa-envelope text-indigo-600 mr-3"></i>
                            <span class="text-gray-600">{{ $project->sales_email }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Available Units -->
    <div class="mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Available Units</h2>
            <div class="text-sm text-gray-500">
                {{ $units->total() }} units available
            </div>
        </div>

        @if($units->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($units as $unit)
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
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Available
                                </span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-lg font-medium text-gray-900 truncate">{{ $unit->unit_code }}</p>
                                    <p class="text-sm text-gray-500">{{ $unit->unitType->name }}</p>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="flex items-center text-sm text-gray-500">
                                    <i class="fas fa-expand-arrows-alt mr-2"></i>
                                    <span>{{ $unit->unitType->land_area }}m² / {{ $unit->unitType->building_area }}m²</span>
                                </div>
                                <div class="flex items-center text-sm text-gray-500 mt-1">
                                    <i class="fas fa-bed mr-2"></i>
                                    <span>{{ $unit->unitType->bedrooms }} Bedrooms, {{ $unit->unitType->bathrooms }} Bathrooms</span>
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

                            <div class="mt-6">
                                <a href="{{ route('catalog.show', $unit) }}" class="w-full bg-indigo-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($units->hasPages())
                <div class="mt-8">
                    {{ $units->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-12">
                <i class="fas fa-home text-gray-400 text-6xl"></i>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No units available</h3>
                <p class="mt-1 text-sm text-gray-500">All units in this project are currently sold or booked.</p>
                <div class="mt-6">
                    <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i> Browse Other Projects
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Back to Catalog -->
    <div class="text-center">
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Catalog
        </a>
    </div>
</div>
@endsection
