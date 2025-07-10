@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-blue-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Welcome back, {{ Auth::user()->name }}!</h1>
                <p class="text-indigo-100 mt-1">Here's what's happening with your real estate business today.</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-indigo-100">{{ now()->format('l, F d, Y') }}</div>
                <div class="text-lg font-semibold">{{ now()->format('H:i') }}</div>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Revenue -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <i class="fas fa-dollar-sign text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_revenue'] ?? 0, 0, ',', '.') }}</div>
                        </dd>
                        <div class="text-sm text-green-600 mt-1">
                            <i class="fas fa-arrow-up"></i> +{{ $stats['revenue_growth'] ?? 0 }}% from last month
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Projects -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                        <i class="fas fa-building text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Active Projects</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['projects'] ?? 0 }}</div>
                        </dd>
                        <div class="text-sm text-indigo-600 mt-1">
                            {{ $stats['new_projects'] ?? 0 }} new this month
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Units Sold -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <i class="fas fa-home text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">Units Sold</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['units_sold'] ?? 0 }}</div>
                        </dd>
                        <div class="text-sm text-purple-600 mt-1">
                            {{ $stats['available_units'] ?? 0 }} available
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Leads -->
        <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <i class="fas fa-users text-white text-xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dt class="text-sm font-medium text-gray-500 truncate">New Leads</dt>
                        <dd class="flex items-baseline">
                            <div class="text-2xl font-semibold text-gray-900">{{ $stats['new_leads'] ?? 0 }}</div>
                        </dd>
                        <div class="text-sm text-blue-600 mt-1">
                            {{ $stats['leads_today'] ?? 0 }} today
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Performance -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Sales Performance</h3>
                <select class="text-sm border-gray-300 rounded-md">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-line text-gray-400 text-4xl mb-2"></i>
                    <p class="text-gray-500">Sales chart will be displayed here</p>
                    <p class="text-xs text-gray-400">Integration with Chart.js or similar library</p>
                </div>
            </div>
        </div>

        <!-- Lead Conversion Funnel -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Lead Conversion Funnel</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">New Leads</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 100%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $stats['funnel']['new_leads'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Contacted</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ $stats['funnel']['contacted_percentage'] ?? 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $stats['funnel']['contacted'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Qualified</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-orange-500 h-2 rounded-full" style="width: {{ $stats['funnel']['qualified_percentage'] ?? 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $stats['funnel']['qualified'] ?? 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Converted</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                            <div class="bg-green-500 h-2 rounded-full" style="width: {{ $stats['funnel']['converted_percentage'] ?? 0 }}%"></div>
                        </div>
                        <span class="text-sm font-medium">{{ $stats['funnel']['converted'] ?? 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects & Units Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing Projects -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Top Performing Projects</h3>
            <div class="space-y-4">
                @forelse($topProjects as $project)
                <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-white"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">{{ $project->name }}</p>
                            <p class="text-sm text-gray-500">{{ $project->location->city ?? 'Location not set' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">{{ $project->units_sold ?? 0 }}/{{ $project->total_units ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Units sold</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-building text-gray-400 text-3xl"></i>
                    <p class="text-gray-500 mt-2">No projects data available</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activities</h3>
            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                <div class="flex">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full 
                            @if($activity['type'] == 'booking') bg-green-100 
                            @elseif($activity['type'] == 'lead') bg-blue-100 
                            @elseif($activity['type'] == 'payment') bg-yellow-100 
                            @else bg-gray-100 @endif 
                            flex items-center justify-center">
                            <i class="fas fa-{{ $activity['icon'] ?? 'info' }} 
                                @if($activity['type'] == 'booking') text-green-600 
                                @elseif($activity['type'] == 'lead') text-blue-600 
                                @elseif($activity['type'] == 'payment') text-yellow-600 
                                @else text-gray-600 @endif 
                                text-sm"></i>
                        </div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $activity['title'] ?? 'Activity' }}</p>
                        <p class="text-sm text-gray-500">{{ $activity['description'] ?? 'No description' }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $activity['time'] ?? 'Unknown time' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="fas fa-history text-gray-400 text-3xl"></i>
                    <p class="text-gray-500 mt-2">No recent activities</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Bookings & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="lg:col-span-2 bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Bookings</h3>
                <a href="{{ route('bookings.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">View all</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600">{{ substr($booking->customer->name ?? 'N/A', 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $booking->customer->name ?? 'Unknown' }}</div>
                                        <div class="text-sm text-gray-500">{{ $booking->booking_code ?? 'No Code' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $booking->unit->unit_code ?? 'N/A' }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->unit->project->name ?? 'Unknown Project' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($booking->total_price ?? 0, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $booking->status == 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $booking->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ !in_array($booking->status ?? '', ['confirmed', 'pending']) ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($booking->status ?? 'unknown') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                No recent bookings found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('bookings.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    New Booking
                </a>
                <a href="{{ route('leads.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user-plus mr-2"></i>
                    Add Lead
                </a>
                <a href="{{ route('customers.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-address-book mr-2"></i>
                    Add Customer
                </a>
                <a href="{{ route('units.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-home mr-2"></i>
                    Add Unit
                </a>
                <a href="{{ route('projects.create') }}" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-building mr-2"></i>
                    New Project
                </a>
            </div>
            
            <!-- Today's Schedule -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h4 class="text-md font-medium text-gray-900 mb-3">Today's Schedule</h4>
                <div class="space-y-2">
                    @forelse($todaySchedule as $schedule)
                    <div class="text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-600">{{ $schedule['time'] ?? 'No time' }}</span>
                            <span class="text-indigo-600">{{ $schedule['type'] ?? 'Unknown' }}</span>
                        </div>
                        <div class="text-gray-900 font-medium">{{ $schedule['title'] ?? 'No title' }}</div>
                    </div>
                    @empty
                    <div class="text-sm text-gray-500 text-center py-2">
                        No scheduled activities for today
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection