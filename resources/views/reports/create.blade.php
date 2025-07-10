@extends('layouts.app')

@section('title', 'Generate Report')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Generate Custom Report</h1>
        <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Reports
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form action="{{ route('reports.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Report Type -->
                <div class="md:col-span-2">
                    <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                    <select name="report_type" id="report_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('report_type') border-red-300 @enderror">
                        <option value="">Select Report Type</option>
                        <option value="sales" {{ old('report_type') == 'sales' ? 'selected' : '' }}>Sales Report</option>
                        <option value="leads" {{ old('report_type') == 'leads' ? 'selected' : '' }}>Leads Report</option>
                        <option value="bookings" {{ old('report_type') == 'bookings' ? 'selected' : '' }}>Bookings Report</option>
                        <option value="projects" {{ old('report_type') == 'projects' ? 'selected' : '' }}>Projects Report</option>
                        <option value="customers" {{ old('report_type') == 'customers' ? 'selected' : '' }}>Customers Report</option>
                        <option value="financial" {{ old('report_type') == 'financial' ? 'selected' : '' }}>Financial Report</option>
                        <option value="inventory" {{ old('report_type') == 'inventory' ? 'selected' : '' }}>Inventory Report</option>
                    </select>
                    @error('report_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Report Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Report Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Format -->
                <div>
                    <label for="format" class="block text-sm font-medium text-gray-700">Output Format</label>
                    <select name="format" id="format" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('format') border-red-300 @enderror">
                        <option value="pdf" {{ old('format') == 'pdf' ? 'selected' : '' }}>PDF</option>
                        <option value="excel" {{ old('format') == 'excel' ? 'selected' : '' }}>Excel</option>
                        <option value="csv" {{ old('format') == 'csv' ? 'selected' : '' }}>CSV</option>
                        <option value="html" {{ old('format') == 'html' ? 'selected' : '' }}>HTML</option>
                    </select>
                    @error('format')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Range -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                    <input type="date" name="date_from" id="date_from" value="{{ old('date_from') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('date_from') border-red-300 @enderror">
                    @error('date_from')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                    <input type="date" name="date_to" id="date_to" value="{{ old('date_to') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('date_to') border-red-300 @enderror">
                    @error('date_to')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Filters -->
                <div class="md:col-span-2">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Filters</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Project Filter -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                            <select name="project_id" id="project_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Projects</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Developer Filter -->
                        <div>
                            <label for="developer_id" class="block text-sm font-medium text-gray-700">Developer</label>
                            <select name="developer_id" id="developer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Developers</option>
                                @foreach($developers as $developer)
                                    <option value="{{ $developer->id }}" {{ old('developer_id') == $developer->id ? 'selected' : '' }}>{{ $developer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Grouping Options -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Group By</label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="group_by[]" value="date" {{ in_array('date', old('group_by', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Date</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="group_by[]" value="project" {{ in_array('project', old('group_by', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Project</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="group_by[]" value="developer" {{ in_array('developer', old('group_by', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Developer</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="group_by[]" value="status" {{ in_array('status', old('group_by', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Status</span>
                        </label>
                    </div>
                    @error('group_by')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Include Options -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Include in Report</label>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="include[]" value="summary" {{ in_array('summary', old('include', ['summary'])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Summary</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="include[]" value="charts" {{ in_array('charts', old('include', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Charts</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="include[]" value="details" {{ in_array('details', old('include', ['details'])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Details</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="include[]" value="attachments" {{ in_array('attachments', old('include', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Attachments</span>
                        </label>
                    </div>
                    @error('include')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 @enderror" placeholder="Optional description for this report...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Save Options -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Save Options</label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="save_template" value="1" {{ old('save_template') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Save as template for future use</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="schedule" value="1" {{ old('schedule') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Schedule for automatic generation</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" name="action" value="preview" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-eye mr-2"></i> Preview
                </button>
                <button type="submit" name="action" value="generate" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-file-download mr-2"></i> Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Set default dates
document.addEventListener('DOMContentLoaded', function() {
    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');
    
    if (!dateFrom.value) {
        const firstDayOfMonth = new Date();
        firstDayOfMonth.setDate(1);
        dateFrom.value = firstDayOfMonth.toISOString().split('T')[0];
    }
    
    if (!dateTo.value) {
        const today = new Date();
        dateTo.value = today.toISOString().split('T')[0];
    }
});
</script>
@endsection
