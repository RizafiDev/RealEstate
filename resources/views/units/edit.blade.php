@extends('layouts.app')

@section('title', 'Edit Unit - ' . $unit->unit_code)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Edit Unit: {{ $unit->unit_code }}</h1>
        <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Unit
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form action="{{ route('units.update', $unit) }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project -->
                <div>
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                    <select id="project_id" name="project_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('project_id') border-red-300 @enderror">
                        <option value="">Select Project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ (old('project_id', $unit->project_id) == $project->id) ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit Type -->
                <div>
                    <label for="unit_type_id" class="block text-sm font-medium text-gray-700">Unit Type</label>
                    <select id="unit_type_id" name="unit_type_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit_type_id') border-red-300 @enderror">
                        <option value="">Select Unit Type</option>
                        @foreach($unitTypes as $type)
                            <option value="{{ $type->id }}" {{ (old('unit_type_id', $unit->unit_type_id) == $type->id) ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('unit_type_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unit Code -->
                <div>
                    <label for="unit_code" class="block text-sm font-medium text-gray-700">Unit Code</label>
                    <input type="text" name="unit_code" id="unit_code" value="{{ old('unit_code', $unit->unit_code) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('unit_code') border-red-300 @enderror">
                    @error('unit_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" name="status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror">
                        <option value="available" {{ (old('status', $unit->status) == 'available') ? 'selected' : '' }}>Available</option>
                        <option value="booked" {{ (old('status', $unit->status) == 'booked') ? 'selected' : '' }}>Booked</option>
                        <option value="sold" {{ (old('status', $unit->status) == 'sold') ? 'selected' : '' }}>Sold</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                    <input type="number" name="price" id="price" value="{{ old('price', $unit->price) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('price') border-red-300 @enderror">
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount Price -->
                <div>
                    <label for="discount_price" class="block text-sm font-medium text-gray-700">Discount Price (Optional)</label>
                    <input type="number" name="discount_price" id="discount_price" value="{{ old('discount_price', $unit->discount_price) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('discount_price') border-red-300 @enderror">
                    @error('discount_price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Facing -->
                <div>
                    <label for="facing" class="block text-sm font-medium text-gray-700">Facing</label>
                    <select id="facing" name="facing" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('facing') border-red-300 @enderror">
                        <option value="">Select Facing</option>
                        <option value="north" {{ (old('facing') ?? $unit->facing) == 'north' ? 'selected' : '' }}>North</option>
                        <option value="south" {{ (old('facing') ?? $unit->facing) == 'south' ? 'selected' : '' }}>South</option>
                        <option value="east" {{ (old('facing') ?? $unit->facing) == 'east' ? 'selected' : '' }}>East</option>
                        <option value="west" {{ (old('facing') ?? $unit->facing) == 'west' ? 'selected' : '' }}>West</option>
                        <option value="northeast" {{ (old('facing') ?? $unit->facing) == 'northeast' ? 'selected' : '' }}>Northeast</option>
                        <option value="northwest" {{ (old('facing') ?? $unit->facing) == 'northwest' ? 'selected' : '' }}>Northwest</option>
                        <option value="southeast" {{ (old('facing') ?? $unit->facing) == 'southeast' ? 'selected' : '' }}>Southeast</option>
                        <option value="southwest" {{ (old('facing') ?? $unit->facing) == 'southwest' ? 'selected' : '' }}>Southwest</option>
                    </select>
                    @error('facing')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Certificate -->
                <div>
                    <label for="certificate" class="block text-sm font-medium text-gray-700">Certificate</label>
                    <input type="text" id="certificate" name="certificate" value="{{ old('certificate') ?? $unit->certificate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('certificate') border-red-300 @enderror" placeholder="e.g., SHM, HGB">
                    @error('certificate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cash Hard Percentage -->
                <div>
                    <label for="cash_hard_percentage" class="block text-sm font-medium text-gray-700">Cash Hard (%)</label>
                    <input type="number" id="cash_hard_percentage" name="cash_hard_percentage" value="{{ old('cash_hard_percentage') ?? $unit->cash_hard_percentage }}" min="0" max="100" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('cash_hard_percentage') border-red-300 @enderror" placeholder="0.00">
                    @error('cash_hard_percentage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Cash Tempo Percentage -->
                <div>
                    <label for="cash_tempo_percentage" class="block text-sm font-medium text-gray-700">Cash Tempo (%)</label>
                    <input type="number" id="cash_tempo_percentage" name="cash_tempo_percentage" value="{{ old('cash_tempo_percentage') ?? $unit->cash_tempo_percentage }}" min="0" max="100" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('cash_tempo_percentage') border-red-300 @enderror" placeholder="0.00">
                    @error('cash_tempo_percentage')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 @enderror" placeholder="Describe this unit...">{{ old('description') ?? $unit->description }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                <textarea id="notes" name="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('notes') border-red-300 @enderror" placeholder="Internal notes...">{{ old('notes') ?? $unit->notes }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Current Images -->
            @if($unit->images && is_array($unit->images) && count($unit->images) > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Images</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($unit->images as $image)
                            <div class="relative">
                                <img src="{{ asset('storage/' . $image) }}" alt="Unit Image" class="h-32 w-full object-cover rounded-lg">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New Images -->
            <div>
                <label for="images" class="block text-sm font-medium text-gray-700">Upload New Images</label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload new images</span>
                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each (will replace existing images)</p>
                    </div>
                </div>
                @error('images')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Update Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('units.show', $unit) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Update Unit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
