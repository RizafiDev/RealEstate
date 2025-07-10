@extends('layouts.app')

@section('title', 'Customers Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Customers Management</h1>
        <div class="flex space-x-3">
            <button onclick="openExportModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-download mr-2"></i> Export
            </button>
            <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Add Customer
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Customers</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_customers'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calendar-check text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Customers</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['active_customers'] ?? 0 }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">Customers with Bookings</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['customers_with_bookings'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star text-yellow-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">VIP Customers</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['vip_customers'] ?? 0 }}</dd>
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
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, email, phone, ID..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="segment" class="block text-sm font-medium text-gray-700">Segment</label>
                <select id="segment" name="segment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Segments</option>
                    <option value="prospect" @if(request('segment') == 'prospect') selected @endif>Prospect</option>
                    <option value="active" @if(request('segment') == 'active') selected @endif>Active</option>
                    <option value="vip" @if(request('segment') == 'vip') selected @endif>VIP</option>
                    <option value="inactive" @if(request('segment') == 'inactive') selected @endif>Inactive</option>
                </select>
            </div>
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <select id="location" name="location" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Locations</option>
                    @if(isset($locations))
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" @if(request('location') == $location->id) selected @endif>{{ $location->city }}, {{ $location->province }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div>
                <label for="date_range" class="block text-sm font-medium text-gray-700">Registration Date</label>
                <select id="date_range" name="date_range" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Time</option>
                    <option value="today" @if(request('date_range') == 'today') selected @endif>Today</option>
                    <option value="week" @if(request('date_range') == 'week') selected @endif>This Week</option>
                    <option value="month" @if(request('date_range') == 'month') selected @endif>This Month</option>
                    <option value="year" @if(request('date_range') == 'year') selected @endif>This Year</option>
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Customers List -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($customers as $customer)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center flex-1 min-w-0">
                            <div class="flex-shrink-0 h-12 w-12">
                                @if($customer->profile_photo)
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ asset('storage/' . $customer->profile_photo) }}" alt="{{ $customer->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-indigo-500 flex items-center justify-center">
                                        <span class="text-lg font-medium text-white">{{ substr($customer->name, 0, 2) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $customer->name }}</p>
                                    @if($customer->segment == 'vip')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-star mr-1"></i> VIP
                                        </span>
                                    @elseif($customer->segment == 'active')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @elseif($customer->segment == 'prospect')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            Prospect
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-1 grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-envelope mr-2"></i>
                                        <span class="truncate">{{ $customer->email }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-phone mr-2"></i>
                                        <span>{{ $customer->phone }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-id-card mr-2"></i>
                                        <span>{{ $customer->id_number ?? 'No ID' }}</span>
                                    </div>
                                </div>
                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($customer->address)
                                        <div class="flex items-center text-sm text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <span class="truncate">{{ $customer->address }}</span>
                                        </div>
                                    @endif
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-calendar mr-2"></i>
                                        <span>Registered {{ $customer->created_at->format('d M Y') }}</span>
                                    </div>
                                </div>
                                
                                <!-- Customer Stats -->
                                <div class="mt-2 flex items-center space-x-6 text-xs text-gray-400">
                                    <span>
                                        <i class="fas fa-calendar-check mr-1"></i>
                                        {{ $customer->bookings_count ?? 0 }} Bookings
                                    </span>
                                    <span>
                                        <i class="fas fa-dollar-sign mr-1"></i>
                                        Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}
                                    </span>
                                    @if($customer->last_interaction_at)
                                        <span>
                                            <i class="fas fa-clock mr-1"></i>
                                            Last contact {{ $customer->last_interaction_at->diffForHumans() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2 ml-4">
                            <!-- Quick Actions -->
                            <div class="flex space-x-1">
                                @if($customer->phone)
                                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $customer->phone) }}" target="_blank" class="text-green-600 hover:text-green-900" title="WhatsApp">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                @endif
                                @if($customer->email)
                                    <a href="mailto:{{ $customer->email }}" class="text-blue-600 hover:text-blue-900" title="Email">
                                        <i class="fas fa-envelope text-lg"></i>
                                    </a>
                                @endif
                                @if($customer->phone)
                                    <a href="tel:{{ $customer->phone }}" class="text-gray-600 hover:text-gray-900" title="Call">
                                        <i class="fas fa-phone text-lg"></i>
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Actions Menu -->
                            <div class="flex space-x-2">
                                <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                                <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                                @if($customer->bookings_count == 0)
                                    <a href="{{ route('bookings.create', ['customer_id' => $customer->id]) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-green-700 bg-green-100 hover:bg-green-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-plus mr-1"></i> Book
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-center py-12">
                    <i class="fas fa-users text-gray-400 text-6xl"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No customers found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first customer.</p>
                    <div class="mt-6">
                        <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i> Add Customer
                        </a>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($customers->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $customers->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Export Customers</h3>
                <button onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('customers.export') }}" method="POST" class="mt-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="export_format" class="block text-sm font-medium text-gray-700">Format</label>
                        <select name="format" id="export_format" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="csv">CSV</option>
                            <option value="excel">Excel</option>
                            <option value="pdf">PDF</option>
                        </select>
                    </div>
                    <div>
                        <label for="export_fields" class="block text-sm font-medium text-gray-700">Fields to Export</label>
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="fields[]" value="name" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Name</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="fields[]" value="email" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Email</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="fields[]" value="phone" checked class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Phone</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="fields[]" value="address" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Address</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="fields[]" value="segment" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Segment</span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeExportModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-download mr-2"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openExportModal() {
    document.getElementById('exportModal').classList.remove('hidden');
}

function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}
</script>
@endpush
@endsection
