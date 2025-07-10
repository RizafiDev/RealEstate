@extends('layouts.app')

@section('title', 'Bookings Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Bookings Management</h1>
        <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-plus mr-2"></i> Create Booking
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Bookings</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_bookings'] ?? 0 }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['pending_bookings'] ?? 0 }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Confirmed</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['confirmed_bookings'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($statistics['total_revenue'] ?? 0, 0, ',', '.') }}</dd>
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
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Booking ID, customer name..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="pending" @if(request('status') == 'pending') selected @endif>Pending</option>
                    <option value="confirmed" @if(request('status') == 'confirmed') selected @endif>Confirmed</option>
                    <option value="paid" @if(request('status') == 'paid') selected @endif>Paid</option>
                    <option value="cancelled" @if(request('status') == 'cancelled') selected @endif>Cancelled</option>
                </select>
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
                <label for="date_range" class="block text-sm font-medium text-gray-700">Date Range</label>
                <select id="date_range" name="date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Time</option>
                    <option value="today" @if(request('date_range') == 'today') selected @endif>Today</option>
                    <option value="week" @if(request('date_range') == 'week') selected @endif>This Week</option>
                    <option value="month" @if(request('date_range') == 'month') selected @endif>This Month</option>
                    <option value="quarter" @if(request('date_range') == 'quarter') selected @endif>This Quarter</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Booking Details
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unit & Project
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Payment
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">#{{ $booking->booking_code }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->created_at->format('d M Y, H:i') }}</div>
                                    @if($booking->booking_date)
                                        <div class="text-xs text-indigo-600">Visit: {{ $booking->booking_date->format('d M Y') }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8">
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">{{ substr($booking->customer->name, 0, 2) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->customer->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->customer->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->customer->phone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $booking->unit->unit_code }}</div>
                                    <div class="text-sm text-gray-500">{{ $booking->unit->unitType->name }}</div>
                                    <div class="text-sm text-indigo-600">{{ $booking->unit->project->name }}</div>
                                    <div class="text-xs text-gray-400">{{ $booking->unit->unitType->building_area }}m²</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">
                                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        DP: Rp {{ number_format($booking->dp_amount, 0, ',', '.') }}
                                    </div>
                                    @if($booking->dp_paid_at)
                                        <div class="text-xs text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>DP Paid {{ $booking->dp_paid_at->format('d M Y') }}
                                        </div>
                                    @else
                                        <div class="text-xs text-yellow-600">
                                            <i class="fas fa-clock mr-1"></i>DP Pending
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($booking->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @elseif($booking->status == 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-check mr-1"></i> Confirmed
                                    </span>
                                @elseif($booking->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-dollar-sign mr-1"></i> Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i> Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('bookings.edit', $booking) }}" class="text-gray-600 hover:text-gray-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($booking->status == 'pending')
                                        <button onclick="confirmBooking({{ $booking->id }})" class="text-green-600 hover:text-green-900" title="Confirm Booking">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    @if($booking->status == 'confirmed' && !$booking->dp_paid_at)
                                        <button onclick="markDpPaid({{ $booking->id }})" class="text-blue-600 hover:text-blue-900" title="Mark DP as Paid">
                                            <i class="fas fa-dollar-sign"></i>
                                        </button>
                                    @endif
                                    @if($booking->customer->phone)
                                        <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $booking->customer->phone) }}?text=Hi {{ $booking->customer->name }}, this is regarding your booking #{{ $booking->booking_code }}" target="_blank" class="text-green-600 hover:text-green-900" title="WhatsApp">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                    @endif
                                    <button onclick="printContract({{ $booking->id }})" class="text-purple-600 hover:text-purple-900" title="Print Contract">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <i class="fas fa-calendar-check text-gray-400 text-6xl"></i>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by creating a new booking.</p>
                                <div class="mt-6">
                                    <a href="{{ route('bookings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-plus mr-2"></i> Create Booking
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($bookings->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
function confirmBooking(bookingId) {
    if (confirm('Are you sure you want to confirm this booking?')) {
        fetch(`/bookings/${bookingId}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error confirming booking');
            }
        });
    }
}

function markDpPaid(bookingId) {
    if (confirm('Mark DP as paid for this booking?')) {
        fetch(`/bookings/${bookingId}/mark-dp-paid`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error marking DP as paid');
            }
        });
    }
}

function printContract(bookingId) {
    window.open(`/bookings/${bookingId}/contract`, '_blank');
}
</script>
@endpush
@endsection
