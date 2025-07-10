@extends('layouts.app')

@section('title', 'Report Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $report->name }}</h1>
            <p class="text-gray-600">{{ ucfirst($report->report_type) }} Report</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('reports.download', $report) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                <i class="fas fa-download mr-2"></i> Download
            </a>
            <a href="{{ route('reports.edit', $report) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('reports.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Reports
            </a>
        </div>
    </div>

    <!-- Report Status -->
    <div class="flex items-center space-x-3">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
            @if($report->status == 'completed') bg-green-100 text-green-800
            @elseif($report->status == 'processing') bg-yellow-100 text-yellow-800
            @elseif($report->status == 'failed') bg-red-100 text-red-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($report->status) }}
        </span>
        
        @if($report->status == 'completed')
        <span class="text-sm text-gray-500">
            Generated {{ $report->completed_at->diffForHumans() }}
        </span>
        @endif
    </div>

    <!-- Report Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Report Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete details about this report.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Report Name</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->name }}</dd>
                </div>
                
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($report->report_type) }}</dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Format</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ strtoupper($report->format) }}</dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Date Range</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $report->date_from->format('d M Y') }} - {{ $report->date_to->format('d M Y') }}
                    </dd>
                </div>

                @if($report->filters)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Filters</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            @foreach(json_decode($report->filters, true) as $key => $value)
                                @if($value)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2 mb-1">
                                    {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value }}
                                </span>
                                @endif
                            @endforeach
                        </div>
                    </dd>
                </div>
                @endif

                @if($report->description)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->description }}</dd>
                </div>
                @endif

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Generated By</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->user->name }}</dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->created_at->format('d M Y H:i') }}</dd>
                </div>

                @if($report->completed_at)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Completed</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->completed_at->format('d M Y H:i') }}</dd>
                </div>
                @endif

                @if($report->file_path && $report->status == 'completed')
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">File Size</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ \Storage::exists($report->file_path) ? number_format(\Storage::size($report->file_path) / 1024, 2) . ' KB' : 'File not found' }}
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Report Summary (if available) -->
    @if($report->status == 'completed' && $report->summary)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Report Summary</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Key metrics and insights from this report.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            @php $summary = json_decode($report->summary, true); @endphp
            
            @if(isset($summary['metrics']))
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                @foreach($summary['metrics'] as $metric => $value)
                <div class="bg-gray-50 overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-chart-bar text-gray-400 text-xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">{{ ucfirst(str_replace('_', ' ', $metric)) }}</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ is_numeric($value) ? number_format($value) : $value }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if(isset($summary['insights']))
            <div class="prose max-w-none">
                <h4>Key Insights</h4>
                <ul>
                    @foreach($summary['insights'] as $insight)
                    <li>{{ $insight }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Report Preview (if HTML format) -->
    @if($report->status == 'completed' && $report->format == 'html' && $report->file_path)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Report Preview</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Preview of the generated report.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="p-6 max-h-96 overflow-y-auto">
                @if(\Storage::exists($report->file_path))
                    {!! \Storage::get($report->file_path) !!}
                @else
                    <p class="text-gray-500">Report file not found.</p>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Actions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Available actions for this report.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex flex-wrap gap-3">
                @if($report->status == 'completed')
                <a href="{{ route('reports.download', $report) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-download mr-2"></i> Download Report
                </a>
                
                <a href="{{ route('reports.share', $report) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-share mr-2"></i> Share Report
                </a>
                
                <button onclick="regenerateReport()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-redo mr-2"></i> Regenerate
                </button>
                @endif
                
                @if($report->status == 'processing')
                <button onclick="cancelReport()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-times mr-2"></i> Cancel Generation
                </button>
                @endif
                
                <a href="{{ route('reports.duplicate', $report) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-copy mr-2"></i> Duplicate
                </a>
                
                <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this report?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-trash mr-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function regenerateReport() {
    if (confirm('Are you sure you want to regenerate this report? This will create a new version with current data.')) {
        // Redirect to regenerate endpoint
        window.location.href = '{{ route("reports.regenerate", $report) }}';
    }
}

function cancelReport() {
    if (confirm('Are you sure you want to cancel the report generation?')) {
        // Send request to cancel endpoint
        fetch('{{ route("reports.cancel", $report) }}', {
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
                alert('Failed to cancel report generation.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while canceling the report.');
        });
    }
}
</script>
@endsection
