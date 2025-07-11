@extends('layouts.app')

@section('title', $unit->unit_code . ' - ' . $unit->project->name)

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
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <a href="{{ route('catalog.project', $unit->project) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600 md:ml-2">
                        {{ $unit->project->name }}
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                    <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ $unit->unit_code }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="lg:grid lg:grid-cols-2 lg:gap-x-8 lg:items-start">
        <!-- Image gallery -->
        <div class="flex flex-col-reverse">
            <!-- Image selector -->
            @if($unit->images && is_array($unit->images) && count($unit->images) > 1)
                <div class="hidden mt-6 w-full max-w-2xl mx-auto sm:block lg:max-w-none">
                    <div class="grid grid-cols-4 gap-6">
                        @foreach($unit->images as $index => $image)
                            <button class="relative h-24 bg-white rounded-md flex items-center justify-center text-sm font-medium uppercase text-gray-900 cursor-pointer hover:bg-gray-50 focus:outline-none focus:ring focus:ring-offset-4 focus:ring-indigo-500">
                                <img src="{{ asset('storage/' . $image) }}" alt="Unit Image {{ $index + 1 }}" class="w-full h-full object-center object-cover rounded-md">
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Main image -->
            <div class="w-full aspect-w-1 aspect-h-1">
                @if($unit->images && is_array($unit->images) && count($unit->images) > 0)
                    <img src="{{ asset('storage/' . $unit->images[0]) }}" alt="{{ $unit->unit_code }}" class="w-full h-full object-center object-cover sm:rounded-lg">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center rounded-lg">
                        <i class="fas fa-home text-gray-400 text-6xl"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product info -->
        <div class="mt-10 px-4 sm:px-0 sm:mt-16 lg:mt-0">
            <h1 class="text-3xl font-extrabold tracking-tight text-gray-900">{{ $unit->unit_code }}</h1>
            
            <div class="mt-3">
                <h2 class="sr-only">Product information</h2>
                <p class="text-3xl text-gray-900 font-bold">Rp {{ number_format($unit->price, 0, ',', '.') }}</p>
                @if($unit->discount_price && $unit->discount_price < $unit->price)
                    <p class="text-lg text-gray-500 line-through">Rp {{ number_format($unit->price, 0, ',', '.') }}</p>
                @endif
            </div>

            <!-- Project Info -->
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-900">Project</h3>
                <div class="mt-2">
                    <p class="text-lg text-indigo-600 font-medium">{{ $unit->project->name }}</p>
                    <p class="text-sm text-gray-500">by {{ $unit->project->developer->name ?? 'Developer' }}</p>
                    <p class="text-sm text-gray-500 flex items-center mt-1">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        {{ $unit->project->location->name ?? '' }}, {{ $unit->project->location->city ?? '' }}
                    </p>
                </div>
            </div>

            <!-- Unit Type Info -->
            <div class="mt-6">
                <h3 class="text-sm font-medium text-gray-900">Unit Specifications</h3>
                <div class="mt-2 space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Type:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->unitType->name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Building Area:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->unitType->building_area }}m²</span>
                    </div>
                    @if($unit->unitType->land_area)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Land Area:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $unit->unitType->land_area }}m²</span>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Bedrooms:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->unitType->bedrooms }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-500">Bathrooms:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $unit->unitType->bathrooms }}</span>
                    </div>
                    @if($unit->facing)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Facing:</span>
                            <span class="text-sm font-medium text-gray-900">{{ ucfirst($unit->facing) }}</span>
                        </div>
                    @endif
                    @if($unit->certificate)
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Certificate:</span>
                            <span class="text-sm font-medium text-gray-900">{{ $unit->certificate }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Options -->
            @if($unit->cash_hard_percentage || $unit->cash_tempo_percentage)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Payment Options</h3>
                    <div class="mt-2 space-y-2">
                        @if($unit->cash_hard_percentage)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Cash Hard:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $unit->cash_hard_percentage }}% discount</span>
                            </div>
                        @endif
                        @if($unit->cash_tempo_percentage)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Cash Tempo:</span>
                                <span class="text-sm font-medium text-gray-900">{{ $unit->cash_tempo_percentage }}% discount</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Description -->
            @if($unit->description)
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-900">Description</h3>
                    <div class="mt-2 prose prose-sm text-gray-500">
                        {{ $unit->description }}
                    </div>
                </div>
            @endif

            <!-- Contact Actions -->
            <div class="mt-10 flex flex-col sm:flex-row sm:space-x-4">
                <button type="button" class="flex-1 bg-indigo-600 border border-transparent rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-phone mr-2"></i>
                    Contact Sales
                </button>
                <button type="button" class="mt-3 sm:mt-0 flex-1 bg-white border border-gray-300 rounded-md py-3 px-8 flex items-center justify-center text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fab fa-whatsapp mr-2"></i>
                    WhatsApp
                </button>
            </div>
        </div>
    </div>

    <!-- Similar Units -->
    @if($similarUnits->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Similar Units in {{ $unit->project->name }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($similarUnits as $similarUnit)
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                        <div class="relative">
                            @if($similarUnit->images && is_array($similarUnit->images) && count($similarUnit->images) > 0)
                                <img class="h-48 w-full object-cover" src="{{ asset('storage/' . $similarUnit->images[0]) }}" alt="{{ $similarUnit->unit_code }}">
                            @else
                                <div class="h-48 w-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-home text-gray-400 text-4xl"></i>
                                </div>
                            @endif
                        </div>

                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <p class="text-lg font-medium text-gray-900 truncate">{{ $similarUnit->unit_code }}</p>
                                    <p class="text-sm text-gray-500">{{ $similarUnit->unitType->name }}</p>
                                </div>
                            </div>

                            <div class="mt-2">
                                <p class="text-xl font-bold text-gray-900">
                                    Rp {{ number_format($similarUnit->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('catalog.show', $similarUnit) }}" class="w-full bg-indigo-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
