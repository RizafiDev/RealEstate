@extends('layouts.app')

@section('title', 'Leads Management')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Leads Management</h1>
        <div class="flex space-x-3">
            <button onclick="openImportModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-upload mr-2"></i> Import Leads
            </button>
            <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-plus mr-2"></i> Add Lead
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Leads</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['total_leads'] ?? 0 }}</dd>
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
                            <dt class="text-sm font-medium text-gray-500 truncate">New Leads</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['new_leads'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-phone text-green-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Contacted</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['contacted_leads'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-handshake text-orange-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Qualified</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['qualified_leads'] ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-trophy text-purple-400 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Converted</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statistics['converted_leads'] ?? 0 }}</dd>
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
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Name, email, phone..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Status</option>
                    <option value="new" @if(request('status') == 'new') selected @endif>New</option>
                    <option value="contacted" @if(request('status') == 'contacted') selected @endif>Contacted</option>
                    <option value="qualified" @if(request('status') == 'qualified') selected @endif>Qualified</option>
                    <option value="proposal" @if(request('status') == 'proposal') selected @endif>Proposal</option>
                    <option value="negotiation" @if(request('status') == 'negotiation') selected @endif>Negotiation</option>
                    <option value="converted" @if(request('status') == 'converted') selected @endif>Converted</option>
                    <option value="lost" @if(request('status') == 'lost') selected @endif>Lost</option>
                </select>
            </div>
            <div>
                <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                <select id="source" name="source" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Sources</option>
                    <option value="website" @if(request('source') == 'website') selected @endif>Website</option>
                    <option value="facebook" @if(request('source') == 'facebook') selected @endif>Facebook</option>
                    <option value="instagram" @if(request('source') == 'instagram') selected @endif>Instagram</option>
                    <option value="referral" @if(request('source') == 'referral') selected @endif>Referral</option>
                    <option value="walk_in" @if(request('source') == 'walk_in') selected @endif>Walk In</option>
                    <option value="other" @if(request('source') == 'other') selected @endif>Other</option>
                </select>
            </div>
            <div>
                <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assigned To</label>
                <select id="assigned_to" name="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">All Agents</option>
                    @foreach($agents as $agent)
                        <option value="{{ $agent->id }}" @if(request('assigned_to') == $agent->id) selected @endif>{{ $agent->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sync-alt mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Leads Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($leads as $lead)
                <li>
                    <div class="px-4 py-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-sm font-medium text-white">{{ substr($lead->name, 0, 2) }}</span>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $lead->name }}</p>
                                    @if($lead->status == 'new')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            New
                                        </span>
                                    @elseif($lead->status == 'contacted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Contacted
                                        </span>
                                    @elseif($lead->status == 'qualified')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Qualified
                                        </span>
                                    @elseif($lead->status == 'converted')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                            Converted
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ ucfirst($lead->status) }}
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center mt-1 space-x-4">
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-envelope mr-1"></i> {{ $lead->email }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-phone mr-1"></i> {{ $lead->phone }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        <i class="fas fa-tag mr-1"></i> {{ ucfirst($lead->source) }}
                                    </p>
                                    @if($lead->assignedTo)
                                        <p class="text-sm text-gray-500">
                                            <i class="fas fa-user mr-1"></i> {{ $lead->assignedTo->name }}
                                        </p>
                                    @endif
                                </div>
                                @if($lead->interested_project)
                                    <p class="text-sm text-indigo-600 mt-1">
                                        <i class="fas fa-building mr-1"></i> Interested in: {{ $lead->interestedProject->name ?? $lead->interested_project }}
                                    </p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    Created {{ $lead->created_at->diffForHumans() }}
                                    @if($lead->last_contacted_at)
                                        • Last contacted {{ $lead->last_contacted_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <!-- Quick Actions -->
                            <div class="flex space-x-1">
                                @if($lead->phone)
                                    <a href="https://wa.me/{{ str_replace(['+', '-', ' '], '', $lead->phone) }}" target="_blank" class="text-green-600 hover:text-green-900" title="WhatsApp">
                                        <i class="fab fa-whatsapp text-lg"></i>
                                    </a>
                                @endif
                                @if($lead->email)
                                    <a href="mailto:{{ $lead->email }}" class="text-blue-600 hover:text-blue-900" title="Email">
                                        <i class="fas fa-envelope text-lg"></i>
                                    </a>
                                @endif
                                @if($lead->phone)
                                    <a href="tel:{{ $lead->phone }}" class="text-gray-600 hover:text-gray-900" title="Call">
                                        <i class="fas fa-phone text-lg"></i>
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Actions Menu -->
                            <div class="flex space-x-2">
                                <a href="{{ route('leads.show', $lead) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    View
                                </a>
                                <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-center py-12">
                    <i class="fas fa-users text-gray-400 text-6xl"></i>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No leads found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by importing leads or adding them manually.</p>
                    <div class="mt-6">
                        <a href="{{ route('leads.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i> Add Lead
                        </a>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($leads->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $leads->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden" style="z-index: 50;">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Import Leads</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                @csrf
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700">Choose CSV File</label>
                    <input type="file" name="file" id="file" accept=".csv" required class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    <p class="mt-1 text-xs text-gray-500">CSV file with columns: name, email, phone, source</p>
                </div>
                <div class="flex items-center justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeImportModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openImportModal() {
    document.getElementById('importModal').classList.remove('hidden');
}

function closeImportModal() {
    document.getElementById('importModal').classList.add('hidden');
}
</script>
@endpush
@endsection
