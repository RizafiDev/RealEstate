@extends('layouts.app')

@section('title', 'Create Lead')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Create New Lead</h1>
        <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Leads
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form action="{{ route('leads.store') }}" method="POST" class="space-y-6 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Customer Selection -->
                <div class="md:col-span-2">
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_id') border-red-300 @enderror">
                        <option value="">Select Existing Customer or Create New</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', request('customer_id')) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }} - {{ $customer->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Or create a new customer by filling the fields below</p>
                </div>

                <!-- New Customer Information (if not selecting existing) -->
                <div class="md:col-span-2 border-t pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">New Customer Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Customer Name -->
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Customer Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_name') border-red-300 @enderror">
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Phone -->
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">Customer Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_phone') border-red-300 @enderror">
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Customer Email -->
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Customer Email</label>
                            <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_email') border-red-300 @enderror">
                            @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Lead Information -->
                <div class="md:col-span-2 border-t pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Lead Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Source -->
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700">Lead Source</label>
                            <select name="source" id="source" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('source') border-red-300 @enderror">
                                <option value="">Select Source</option>
                                <option value="website" {{ old('source') == 'website' ? 'selected' : '' }}>Website</option>
                                <option value="social_media" {{ old('source') == 'social_media' ? 'selected' : '' }}>Social Media</option>
                                <option value="referral" {{ old('source') == 'referral' ? 'selected' : '' }}>Referral</option>
                                <option value="advertisement" {{ old('source') == 'advertisement' ? 'selected' : '' }}>Advertisement</option>
                                <option value="walk_in" {{ old('source') == 'walk_in' ? 'selected' : '' }}>Walk-in</option>
                                <option value="phone_call" {{ old('source') == 'phone_call' ? 'selected' : '' }}>Phone Call</option>
                                <option value="event" {{ old('source') == 'event' ? 'selected' : '' }}>Event/Exhibition</option>
                                <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('source')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Lead Status</label>
                            <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror">
                                <option value="">Select Status</option>
                                <option value="cold" {{ old('status') == 'cold' ? 'selected' : '' }}>Cold</option>
                                <option value="warm" {{ old('status') == 'warm' ? 'selected' : '' }}>Warm</option>
                                <option value="hot" {{ old('status') == 'hot' ? 'selected' : '' }}>Hot</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Interest -->
                        <div>
                            <label for="interest" class="block text-sm font-medium text-gray-700">Area of Interest</label>
                            <input type="text" name="interest" id="interest" value="{{ old('interest') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('interest') border-red-300 @enderror" placeholder="e.g., 2-bedroom apartment, house with garden">
                            @error('interest')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Budget -->
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-700">Budget Range</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="budget" id="budget" value="{{ old('budget') }}" class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('budget') border-red-300 @enderror">
                            </div>
                            @error('budget')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Follow-up Date -->
                        <div>
                            <label for="follow_up_date" class="block text-sm font-medium text-gray-700">Next Follow-up Date</label>
                            <input type="date" name="follow_up_date" id="follow_up_date" value="{{ old('follow_up_date') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('follow_up_date') border-red-300 @enderror">
                            @error('follow_up_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Assigned User -->
                        <div>
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign To</label>
                            <select name="assigned_to" id="assigned_to" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('assigned_to') border-red-300 @enderror">
                                <option value="">Select User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to', auth()->id()) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-300 @enderror" placeholder="Additional notes about this lead...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Create Lead
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Toggle customer fields based on selection
document.getElementById('customer_id').addEventListener('change', function() {
    const customerFields = document.querySelectorAll('#customer_name, #customer_phone, #customer_email');
    if (this.value) {
        // If existing customer selected, disable new customer fields
        customerFields.forEach(field => {
            field.disabled = true;
            field.required = false;
        });
    } else {
        // If no customer selected, enable new customer fields
        customerFields.forEach(field => {
            field.disabled = false;
        });
        document.getElementById('customer_name').required = true;
        document.getElementById('customer_phone').required = true;
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('customer_id').dispatchEvent(new Event('change'));
});
</script>
@endsection
