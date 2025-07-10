@extends('layouts.app')

@section('title', 'Booking Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Booking Details</h1>
            <p class="text-gray-600">Booking ID: {{ $booking->booking_number }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('bookings.edit', $booking) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
            @if($booking->status == 'confirmed') bg-green-100 text-green-800
            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($booking->status) }}
        </span>
    </div>

    <!-- Booking Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Booking Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete details about this booking.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Booking Number</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->booking_number }}</dd>
                </div>
                
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('customers.show', $booking->customer) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $booking->customer->name }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Unit</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('units.show', $booking->unit) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $booking->unit->unit_number }} - {{ $booking->unit->unitType->name }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Project</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('projects.show', $booking->unit->project) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $booking->unit->project->name }}
                        </a>
                    </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Booking Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->booking_date->format('d M Y') }}</dd>
                </div>

                @if($booking->booking_fee)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Booking Fee</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Rp {{ number_format($booking->booking_fee, 0, ',', '.') }}</dd>
                </div>
                @endif

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($booking->status == 'confirmed') bg-green-100 text-green-800
                            @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </dd>
                </div>

                @if($booking->notes)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->notes }}</dd>
                </div>
                @endif

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->created_at->format('d M Y H:i') }}</dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $booking->updated_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Related Contract -->
    @if($booking->contract)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Related Contract</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Contract information for this booking.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-900">Contract Number: {{ $booking->contract->contract_number }}</p>
                    <p class="text-sm text-gray-600">Total Price: Rp {{ number_format($booking->contract->total_price, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('contracts.show', $booking->contract) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-100 hover:bg-indigo-200">
                    View Contract
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-3">
        @if($booking->status == 'pending')
        <form action="{{ route('bookings.update', $booking) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="confirmed">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-check mr-2"></i> Confirm Booking
            </button>
        </form>
        @endif
        
        @if($booking->status != 'cancelled')
        <form action="{{ route('bookings.update', $booking) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel this booking?')">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="cancelled">
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <i class="fas fa-times mr-2"></i> Cancel Booking
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
