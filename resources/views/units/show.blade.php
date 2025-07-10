@extends('layouts.app')

@section('title', 'Unit Details - ' . $unit->unit_code)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $unit->unit_code }}</h1>
            <p class="text-gray-600">{{ $unit->project->name ?? 'No Project' }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('units.edit', $unit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit Unit
            </a>
            <a href="{{ route('units.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Units
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Images -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Unit Images</h3>
                    @if($unit->images && count($unit->images) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($unit->images as $image)
                                <img src="{{ asset('storage/' . $image) }}" alt="Unit Image" class="w-full h-48 object-cover rounded-lg">
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-images text-gray-400 text-4xl"></i>
                            <p class="text-gray-500 mt-2">No images available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Details -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Unit Details</h3>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Unit Code</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->unit_code }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1">
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
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Price</dt>
                            <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($unit->price, 0, ',', '.') }}</dd>
                        </div>
                        @if($unit->discount_price)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Discount Price</dt>
                            <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($unit->discount_price, 0, ',', '.') }}</dd>
                        </div>
                        @endif
                        @if($unit->facing)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Facing</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($unit->facing) }}</dd>
                        </div>
                        @endif
                        @if($unit->certificate)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Certificate</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->certificate }}</dd>
                        </div>
                        @endif
                    </dl>

                    @if($unit->description)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                        <dd class="text-sm text-gray-900">{{ $unit->description }}</dd>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Unit Type Info -->
            @if($unit->unitType)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Unit Type</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Type Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->unitType->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Building Area</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->unitType->building_area }}m²</dd>
                        </div>
                        @if($unit->unitType->land_area)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Land Area</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->unitType->land_area }}m²</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bedrooms</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->unitType->bedrooms }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bathrooms</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->unitType->bathrooms }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
            @endif

            <!-- Project Info -->
            @if($unit->project)
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Project Information</h3>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Project Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->project->name }}</dd>
                        </div>
                        @if($unit->project->developer)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Developer</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->project->developer->name }}</dd>
                        </div>
                        @endif
                        @if($unit->project->location)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Location</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $unit->project->location->city }}, {{ $unit->project->location->province }}</dd>
                        </div>
                        @endif
                    </dl>
                    <div class="mt-4">
                        <a href="{{ route('projects.show', $unit->project) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            View Project Details →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        @if($unit->status == 'available')
                            <a href="{{ route('bookings.create', ['unit_id' => $unit->id]) }}" class="w-full bg-green-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 block">
                                <i class="fas fa-calendar-plus mr-2"></i> Create Booking
                            </a>
                        @endif
                        <a href="{{ route('catalog.show', $unit) }}" target="_blank" class="w-full bg-blue-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 block">
                            <i class="fas fa-external-link-alt mr-2"></i> View in Catalog
                        </a>
                        <button onclick="printUnitDetails()" class="w-full bg-gray-600 text-white text-center px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <i class="fas fa-print mr-2"></i> Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function printUnitDetails() {
    window.print();
}
</script>
@endpush
@endsection
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pricing Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Pricing</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <div class="text-3xl font-bold text-gray-900">
                            Rp {{ number_format($unit->price, 0, ',', '.') }}
                        </div>
                        @if($unit->discount_price && $unit->discount_price < $unit->price)
                            <div class="text-lg text-gray-500 line-through">
                                Rp {{ number_format($unit->price, 0, ',', '.') }}
                            </div>
                            <div class="text-sm text-red-600">
                                Save Rp {{ number_format($unit->price - $unit->discount_price, 0, ',', '.') }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Price per m² -->
                    <div class="text-sm text-gray-600">
                        Rp {{ number_format($unit->price / $unit->unitType->building_area, 0, ',', '.') }}/m²
                    </div>

                    <!-- Payment Options -->
                    <div class="border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Payment Options</h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div>• Cash Hard: {{ $unit->cash_hard_percentage ?? 5 }}% discount</div>
                            <div>• Cash Tempo: {{ $unit->cash_tempo_percentage ?? 3 }}% discount</div>
                            <div>• KPR: Available with major banks</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Project Info -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Location</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">{{ $unit->project->name }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $unit->project->description }}</p>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-gray-400 w-4 mt-1"></i>
                            <div class="ml-2">
                                <div class="text-sm text-gray-900">{{ $unit->project->address }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $unit->project->location->city }}, {{ $unit->project->location->province }}
                                </div>
                            </div>
                        </div>
                        
                        @if($unit->project->phone)
                        <div class="flex items-center">
                            <i class="fas fa-phone text-gray-400 w-4"></i>
                            <span class="ml-2 text-sm text-gray-600">{{ $unit->project->phone }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Quick Actions -->
                    <div class="border-t pt-4 space-y-2">
                        @if($unit->status == 'available')
                            <a href="{{ route('bookings.create', ['unit_id' => $unit->id]) }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-calendar-plus mr-2"></i> Book This Unit
                            </a>
                        @endif
                        <button onclick="shareUnit()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-share mr-2"></i> Share
                        </button>
                        <button onclick="downloadBrochure()" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-download mr-2"></i> Download Brochure
                        </button>
                    </div>
                </div>
            </div>

            <!-- Contact Sales -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white">
                <h3 class="text-lg font-medium mb-2">Interested? Contact Our Sales</h3>
                <p class="text-indigo-100 text-sm mb-4">Get professional consultation and special offers.</p>
                <div class="space-y-2">
                    @if($unit->project->sales_phone)
                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $unit->project->sales_phone) }}?text=Hi, I'm interested in unit {{ $unit->unit_code }} at {{ $unit->project->name }}" target="_blank" class="w-full flex items-center justify-center px-4 py-2 border border-white text-sm font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50">
                        <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                    </a>
                    @endif
                    @if($unit->project->sales_email)
                    <a href="mailto:{{ $unit->project->sales_email }}?subject=Inquiry about {{ $unit->unit_code }}" class="w-full flex items-center justify-center px-4 py-2 border border-white text-sm font-medium rounded-md text-white bg-transparent hover:bg-white hover:text-indigo-600">
                        <i class="fas fa-envelope mr-2"></i> Email
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function changeMainImage(imageSrc, thumbnail) {
    document.getElementById('mainImage').src = imageSrc;
    
    // Remove ring from all thumbnails
    document.querySelectorAll('.grid img').forEach(img => {
        img.classList.remove('ring-2', 'ring-indigo-500');
    });
    
    // Add ring to clicked thumbnail
    thumbnail.classList.add('ring-2', 'ring-indigo-500');
}

function shareUnit() {
    if (navigator.share) {
        navigator.share({
            title: 'Unit {{ $unit->unit_code }} - {{ $unit->project->name }}',
            text: 'Check out this amazing unit for sale!',
            url: window.location.href
        });
    } else {
        // Fallback for browsers that don't support Web Share API
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Unit link copied to clipboard!');
        });
    }
}

function downloadBrochure() {
    // This would typically download a PDF brochure
    alert('Brochure download feature would be implemented here');
}

function printUnitDetails() {
    window.print();
}
</script>
@endpush
@endsection
