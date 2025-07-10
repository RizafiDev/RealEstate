@extends('layouts.app')

@section('title', 'Lead Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Lead Details</h1>
            <p class="text-gray-600">{{ $lead->customer->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-arrow-left mr-2"></i> Back to Leads
            </a>
        </div>
    </div>

    <!-- Status Badge -->
    <div class="flex items-center space-x-3">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
            @if($lead->status == 'hot') bg-red-100 text-red-800
            @elseif($lead->status == 'warm') bg-yellow-100 text-yellow-800
            @elseif($lead->status == 'cold') bg-blue-100 text-blue-800
            @elseif($lead->status == 'converted') bg-green-100 text-green-800
            @else bg-gray-100 text-gray-800
            @endif">
            {{ ucfirst($lead->status) }}
        </span>
        
        @if($lead->follow_up_date && $lead->follow_up_date->isPast() && $lead->status != 'converted')
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
            <i class="fas fa-exclamation-triangle mr-1"></i> Follow-up Overdue
        </span>
        @endif
    </div>

    <!-- Lead Information -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Lead Information</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete details about this lead.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
            <dl class="sm:divide-y sm:divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Customer</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <a href="{{ route('customers.show', $lead->customer) }}" class="text-indigo-600 hover:text-indigo-900">
                            {{ $lead->customer->name }}
                        </a>
                    </dd>
                </div>
                
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Contact</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="space-y-1">
                            <div>
                                <a href="tel:{{ $lead->customer->phone }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-phone mr-1"></i> {{ $lead->customer->phone }}
                                </a>
                            </div>
                            @if($lead->customer->email)
                            <div>
                                <a href="mailto:{{ $lead->customer->email }}" class="text-indigo-600 hover:text-indigo-900">
                                    <i class="fas fa-envelope mr-1"></i> {{ $lead->customer->email }}
                                </a>
                            </div>
                            @endif
                        </div>
                    </dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst(str_replace('_', ' ', $lead->source)) }}</dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            @if($lead->status == 'hot') bg-red-100 text-red-800
                            @elseif($lead->status == 'warm') bg-yellow-100 text-yellow-800
                            @elseif($lead->status == 'cold') bg-blue-100 text-blue-800
                            @elseif($lead->status == 'converted') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </dd>
                </div>

                @if($lead->interest)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Interest</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lead->interest }}</dd>
                </div>
                @endif

                @if($lead->budget)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Budget</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">Rp {{ number_format($lead->budget, 0, ',', '.') }}</dd>
                </div>
                @endif

                @if($lead->assignedTo)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lead->assignedTo->name }}</dd>
                </div>
                @endif

                @if($lead->follow_up_date)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Next Follow-up</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        {{ $lead->follow_up_date->format('d M Y') }}
                        @if($lead->follow_up_date->isPast() && $lead->status != 'converted')
                            <span class="text-red-600 font-medium">(Overdue)</span>
                        @elseif($lead->follow_up_date->isToday())
                            <span class="text-yellow-600 font-medium">(Today)</span>
                        @elseif($lead->follow_up_date->isTomorrow())
                            <span class="text-blue-600 font-medium">(Tomorrow)</span>
                        @endif
                    </dd>
                </div>
                @endif

                @if($lead->notes)
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lead->notes }}</dd>
                </div>
                @endif

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lead->created_at->format('d M Y H:i') }}</dd>
                </div>

                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $lead->updated_at->format('d M Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Lead Activities -->
    @if($lead->activities->count() > 0)
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Lead Activities</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Timeline of activities for this lead.</p>
        </div>
        <div class="border-t border-gray-200">
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($lead->activities as $activity)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex space-x-3 px-6 py-4">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white">
                                        <i class="fas fa-comment text-white text-sm"></i>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                    <div>
                                        <p class="text-sm text-gray-900">{{ $activity->activity_type }}</p>
                                        <p class="text-sm text-gray-500">{{ $activity->description }}</p>
                                    </div>
                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Quick Actions</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Common actions for this lead.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <div class="flex flex-wrap gap-3">
                @if($lead->status != 'converted')
                <a href="{{ route('bookings.create', ['customer_id' => $lead->customer->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <i class="fas fa-check mr-2"></i> Convert to Booking
                </a>
                @endif
                
                <a href="tel:{{ $lead->customer->phone }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-phone mr-2"></i> Call Customer
                </a>
                
                @if($lead->customer->email)
                <a href="mailto:{{ $lead->customer->email }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-envelope mr-2"></i> Send Email
                </a>
                @endif
                
                <button type="button" onclick="addActivity()" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i> Add Activity
                </button>
            </div>
        </div>
    </div>

    <!-- Status Update Actions -->
    @if($lead->status != 'converted')
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Update Lead Status</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Change the status of this lead.</p>
        </div>
        <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
            <form action="{{ route('leads.update', $lead) }}" method="POST" class="flex flex-wrap gap-3">
                @csrf
                @method('PATCH')
                
                @if($lead->status != 'hot')
                <button type="submit" name="status" value="hot" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="fas fa-fire mr-2"></i> Mark as Hot
                </button>
                @endif
                
                @if($lead->status != 'warm')
                <button type="submit" name="status" value="warm" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    <i class="fas fa-thermometer-half mr-2"></i> Mark as Warm
                </button>
                @endif
                
                @if($lead->status != 'cold')
                <button type="submit" name="status" value="cold" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-snowflake mr-2"></i> Mark as Cold
                </button>
                @endif
            </form>
        </div>
    </div>
    @endif
</div>

<script>
function addActivity() {
    // This would open a modal or redirect to add activity page
    // For now, we'll just alert
    alert('Add activity functionality would be implemented here');
}
</script>
@endsection
