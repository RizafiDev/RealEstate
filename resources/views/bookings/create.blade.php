@extends('layouts.app')

@section('title', 'Create Booking')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Create New Booking</h1>
        <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Bookings
        </a>
    </div>

    <!-- Create Form -->
    <form action="{{ route('bookings.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Unit Selection -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Unit Selection</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                        <select name="project_id" id="project_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('project_id') border-red-300 @enderror" onchange="loadUnits()">
                            <option value="">Select Project</option>
                            @foreach($projects ?? [] as $project)
                                <option value="{{ $project->id }}" @if(old('project_id') == $project->id) selected @endif>{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700">Unit</label>
                        <select name="unit_id" id="unit_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit_id') border-red-300 @enderror" onchange="loadUnitDetails()">
                            <option value="">Select Unit</option>
                            @if(old('project_id'))
                                @foreach($units->where('project_id', old('project_id')) ?? [] as $unit)
                                    <option value="{{ $unit->id }}" @if(old('unit_id') == $unit->id) selected @endif>{{ $unit->unit_code }} - {{ $unit->unitType->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('unit_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Unit Details Display -->
                <div id="unitDetails" class="mt-6 hidden">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-900 mb-3">Unit Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Price:</span>
                                <span id="unitPrice" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-500">Type:</span>
                                <span id="unitType" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                            <div>
                                <span class="text-gray-500">Size:</span>
                                <span id="unitSize" class="font-medium text-gray-900 ml-2"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="customer_id" class="block text-sm font-medium text-gray-700">Existing Customer</label>
                        <select name="customer_id" id="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_id') border-red-300 @enderror" onchange="toggleCustomerForm()">
                            <option value="">Select Existing Customer</option>
                            @foreach($customers ?? [] as $customer)
                                <option value="{{ $customer->id }}" @if(old('customer_id') == $customer->id) selected @endif>{{ $customer->name }} - {{ $customer->email }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-end">
                        <label class="inline-flex items-center">
                            <input type="checkbox" id="newCustomer" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" onchange="toggleCustomerForm()">
                            <span class="ml-2 text-sm text-gray-700">Create new customer</span>
                        </label>
                    </div>
                </div>

                <!-- New Customer Form -->
                <div id="newCustomerForm" class="mt-6 hidden">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="customer_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                            <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_name') border-red-300 @enderror">
                            @error('customer_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="customer_email" id="customer_email" value="{{ old('customer_email') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_email') border-red-300 @enderror">
                            @error('customer_email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                            <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_phone') border-red-300 @enderror">
                            @error('customer_phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="customer_ktp" class="block text-sm font-medium text-gray-700">ID Number (KTP)</label>
                            <input type="text" name="customer_ktp" id="customer_ktp" value="{{ old('customer_ktp') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_ktp') border-red-300 @enderror">
                            @error('customer_ktp')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-6">
                        <label for="customer_address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="customer_address" id="customer_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_address') border-red-300 @enderror">{{ old('customer_address') }}</textarea>
                        @error('customer_address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Details</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="booking_date" class="block text-sm font-medium text-gray-700">Booking Date</label>
                        <input type="date" name="booking_date" id="booking_date" value="{{ old('booking_date', date('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('booking_date') border-red-300 @enderror">
                        @error('booking_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="expired_at" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                        <input type="date" name="expired_at" id="expired_at" value="{{ old('expired_at', date('Y-m-d', strtotime('+30 days'))) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('expired_at') border-red-300 @enderror">
                        @error('expired_at')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="sales_agent_id" class="block text-sm font-medium text-gray-700">Sales Agent</label>
                        <select name="sales_agent_id" id="sales_agent_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sales_agent_id') border-red-300 @enderror">
                            <option value="">Select Sales Agent</option>
                            @foreach($salesAgents ?? [] as $agent)
                                <option value="{{ $agent->id }}" @if(old('sales_agent_id') == $agent->id) selected @endif>{{ $agent->name }}</option>
                            @endforeach
                        </select>
                        @error('sales_agent_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror">
                            <option value="pending" @if(old('status') == 'pending') selected @endif>Pending</option>
                            <option value="confirmed" @if(old('status') == 'confirmed') selected @endif>Confirmed</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                        <input type="number" name="total_price" id="total_price" value="{{ old('total_price') }}" step="0.01" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 @error('total_price') border-red-300 @enderror">
                        @error('total_price')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="dp_percentage" class="block text-sm font-medium text-gray-700">Down Payment (%)</label>
                        <input type="number" name="dp_percentage" id="dp_percentage" value="{{ old('dp_percentage', 10) }}" min="1" max="100" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('dp_percentage') border-red-300 @enderror" onchange="calculateDP()">
                        @error('dp_percentage')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="dp_amount" class="block text-sm font-medium text-gray-700">Down Payment Amount</label>
                        <input type="number" name="dp_amount" id="dp_amount" value="{{ old('dp_amount') }}" step="0.01" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 @error('dp_amount') border-red-300 @enderror">
                        @error('dp_amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="remaining_amount" class="block text-sm font-medium text-gray-700">Remaining Amount</label>
                        <input type="number" name="remaining_amount" id="remaining_amount" value="{{ old('remaining_amount') }}" step="0.01" readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm bg-gray-50 @error('remaining_amount') border-red-300 @enderror">
                        @error('remaining_amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Notes</h3>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-300 @enderror" placeholder="Any additional notes or special requirements...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('bookings.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-save mr-2"></i> Create Booking
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function toggleCustomerForm() {
    const customerSelect = document.getElementById('customer_id');
    const newCustomerCheckbox = document.getElementById('newCustomer');
    const newCustomerForm = document.getElementById('newCustomerForm');
    
    if (newCustomerCheckbox.checked) {
        customerSelect.value = '';
        customerSelect.disabled = true;
        newCustomerForm.classList.remove('hidden');
    } else {
        customerSelect.disabled = false;
        newCustomerForm.classList.add('hidden');
    }
    
    if (customerSelect.value) {
        newCustomerCheckbox.checked = false;
        newCustomerForm.classList.add('hidden');
    }
}

function loadUnits() {
    const projectId = document.getElementById('project_id').value;
    const unitSelect = document.getElementById('unit_id');
    
    if (!projectId) {
        unitSelect.innerHTML = '<option value="">Select Unit</option>';
        document.getElementById('unitDetails').classList.add('hidden');
        return;
    }
    
    // This would typically make an AJAX call to load units
    // For now, we'll use the existing units data
    fetch(`/api/units/project/${projectId}`)
        .then(response => response.json())
        .then(data => {
            unitSelect.innerHTML = '<option value="">Select Unit</option>';
            data.units.forEach(unit => {
                unitSelect.innerHTML += `<option value="${unit.id}">${unit.unit_code} - ${unit.unit_type.name}</option>`;
            });
        })
        .catch(error => {
            console.error('Error loading units:', error);
        });
}

function loadUnitDetails() {
    const unitId = document.getElementById('unit_id').value;
    
    if (!unitId) {
        document.getElementById('unitDetails').classList.add('hidden');
        document.getElementById('total_price').value = '';
        calculateDP();
        return;
    }
    
    // This would typically make an AJAX call to load unit details
    fetch(`/api/units/${unitId}`)
        .then(response => response.json())
        .then(data => {
            const unit = data.unit;
            document.getElementById('unitPrice').textContent = `Rp ${numberFormat(unit.price)}`;
            document.getElementById('unitType').textContent = unit.unit_type.name;
            document.getElementById('unitSize').textContent = `${unit.unit_type.building_area}m²`;
            document.getElementById('total_price').value = unit.price;
            document.getElementById('unitDetails').classList.remove('hidden');
            calculateDP();
        })
        .catch(error => {
            console.error('Error loading unit details:', error);
        });
}

function calculateDP() {
    const totalPrice = parseFloat(document.getElementById('total_price').value) || 0;
    const dpPercentage = parseFloat(document.getElementById('dp_percentage').value) || 0;
    
    const dpAmount = (totalPrice * dpPercentage) / 100;
    const remainingAmount = totalPrice - dpAmount;
    
    document.getElementById('dp_amount').value = dpAmount.toFixed(2);
    document.getElementById('remaining_amount').value = remainingAmount.toFixed(2);
}

function numberFormat(num) {
    return new Intl.NumberFormat('id-ID').format(num);
}

// Initialize form
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listeners
    document.getElementById('dp_percentage').addEventListener('input', calculateDP);
    
    // If there's old input data, trigger the appropriate functions
    if (document.getElementById('project_id').value) {
        loadUnits();
    }
    if (document.getElementById('unit_id').value) {
        loadUnitDetails();
    }
});
</script>
@endpush
@endsection