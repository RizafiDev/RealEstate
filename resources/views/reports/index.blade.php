@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Reports & Analytics</h1>
        <div class="flex space-x-3">
            <button onclick="exportReport()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-download mr-2"></i> Export
            </button>
            <button onclick="scheduleReport()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-clock mr-2"></i> Schedule Report
            </button>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white shadow rounded-lg p-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from', now()->subMonth()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to', now()->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Apply Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-green-500 rounded-md p-3">
                            <i class="fas fa-dollar-sign text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($metrics['total_revenue'] ?? 0, 0, ',', '.') }}</dd>
                        </dl>
                        <div class="flex items-center text-sm {{ ($metrics['revenue_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas fa-arrow-{{ ($metrics['revenue_growth'] ?? 0) >= 0 ? 'up' : 'down' }} mr-1"></i>
                            {{ abs($metrics['revenue_growth'] ?? 0) }}% from last period
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-blue-500 rounded-md p-3">
                            <i class="fas fa-home text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Units Sold</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $metrics['units_sold'] ?? 0 }}</dd>
                        </dl>
                        <div class="flex items-center text-sm {{ ($metrics['sales_growth'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            <i class="fas fa-arrow-{{ ($metrics['sales_growth'] ?? 0) >= 0 ? 'up' : 'down' }} mr-1"></i>
                            {{ abs($metrics['sales_growth'] ?? 0) }}% from last period
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-purple-500 rounded-md p-3">
                            <i class="fas fa-percentage text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Conversion Rate</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($metrics['conversion_rate'] ?? 0, 1) }}%</dd>
                        </dl>
                        <div class="text-sm text-gray-500">
                            From {{ $metrics['total_leads'] ?? 0 }} leads
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="bg-yellow-500 rounded-md p-3">
                            <i class="fas fa-calculator text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Avg Sale Price</dt>
                            <dd class="text-lg font-medium text-gray-900">Rp {{ number_format($metrics['avg_sale_price'] ?? 0, 0, ',', '.') }}</dd>
                        </dl>
                        <div class="text-sm text-gray-500">
                            Per unit sold
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Sales Trend Chart -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Sales Trend</h3>
                <select onchange="updateSalesChart(this.value)" class="text-sm border-gray-300 rounded-md">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly" selected>Monthly</option>
                </select>
            </div>
            <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-line text-gray-400 text-4xl mb-2"></i>
                    <p class="text-gray-500">Sales trend chart</p>
                    <p class="text-xs text-gray-400">Chart.js integration required</p>
                </div>
            </div>
        </div>

        <!-- Project Performance -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Project Performance</h3>
            <div class="h-80 bg-gray-50 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-pie text-gray-400 text-4xl mb-2"></i>
                    <p class="text-gray-500">Project performance chart</p>
                    <p class="text-xs text-gray-400">Chart.js integration required</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Reports -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing Projects -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top Performing Projects</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topProjects as $project)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                <div class="text-sm text-gray-500">{{ $project->location->city ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $project->units_sold ?? 0 }}/{{ $project->total_units ?? 0 }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($project->revenue ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                No data available for selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sales Agent Performance -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Sales Agent Performance</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topAgents as $agent)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600">{{ substr($agent->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $agent->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $agent->role ?? 'Sales Agent' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $agent->sales_count ?? 0 }} units
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                Rp {{ number_format($agent->sales_revenue ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                No sales data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Lead Analytics -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Lead Analytics</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Lead Sources -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Lead Sources</h4>
                    <div class="space-y-2">
                        @foreach($leadSources as $source)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ ucfirst($source->source) }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $source->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $source->percentage }}%"></div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Lead Status -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Lead Status</h4>
                    <div class="space-y-2">
                        @foreach($leadStatuses as $status)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ ucfirst($status->status) }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $status->count }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $status->color ?? 'blue' }}-600 h-2 rounded-full" style="width: {{ $status->percentage }}%"></div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Monthly Lead Trend -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Monthly Trend</h4>
                    <div class="space-y-2">
                        @foreach($monthlyLeads as $month)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $month->month_name }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $month->count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Conversion Funnel -->
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-3">Conversion Funnel</h4>
                    <div class="space-y-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-gray-900">{{ $conversionFunnel['leads'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Total Leads</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">{{ $conversionFunnel['qualified'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Qualified ({{ number_format(($conversionFunnel['qualified'] ?? 0) / max($conversionFunnel['leads'] ?? 1, 1) * 100, 1) }}%)</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold text-green-600">{{ $conversionFunnel['converted'] ?? 0 }}</div>
                            <div class="text-sm text-gray-500">Converted ({{ number_format(($conversionFunnel['converted'] ?? 0) / max($conversionFunnel['leads'] ?? 1, 1) * 100, 1) }}%)</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function exportReport() {
    // Implementation for exporting reports
    const params = new URLSearchParams(window.location.search);
    const exportUrl = new URL('/reports/export', window.location.origin);
    params.forEach((value, key) => exportUrl.searchParams.set(key, value));
    
    window.open(exportUrl.toString(), '_blank');
}

function scheduleReport() {
    // Implementation for scheduling reports
    alert('Schedule report feature would be implemented here');
}

function updateSalesChart(period) {
    // Implementation for updating chart based on period
    console.log('Updating chart for period:', period);
    // This would typically make an AJAX request to get new data
}
</script>
@endpush
@endsection
