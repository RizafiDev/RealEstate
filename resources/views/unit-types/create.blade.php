@extends('layouts.app')

@section('title', 'Create Unit Type')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Create New Unit Type</h1>
        <a href="{{ route('unit-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Unit Types
        </a>
    </div>

    <!-- Create Form -->
    <form action="{{ route('unit-types.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Basic Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Unit Type Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700">Project</label>
                        <select name="project_id" id="project_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('project_id') border-red-300 @enderror">
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
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('category') border-red-300 @enderror">
                            <option value="">Select Category</option>
                            <option value="residential" @if(old('category') == 'residential') selected @endif>Residential</option>
                            <option value="commercial" @if(old('category') == 'commercial') selected @endif>Commercial</option>
                        </select>
                        @error('category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('status') border-red-300 @enderror">
                            <option value="active" @if(old('status') == 'active') selected @endif>Active</option>
                            <option value="inactive" @if(old('status') == 'inactive') selected @endif>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-300 @enderror" placeholder="Describe this unit type...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Specifications -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Specifications</h3>
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                        <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('bedrooms') border-red-300 @enderror">
                        @error('bedrooms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                        <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('bathrooms') border-red-300 @enderror">
                        @error('bathrooms')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="building_area" class="block text-sm font-medium text-gray-700">Building Area (m²)</label>
                        <input type="number" name="building_area" id="building_area" value="{{ old('building_area') }}" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('building_area') border-red-300 @enderror">
                        @error('building_area')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="land_area" class="block text-sm font-medium text-gray-700">Land Area (m²)</label>
                        <input type="number" name="land_area" id="land_area" value="{{ old('land_area') }}" min="0" step="0.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('land_area') border-red-300 @enderror">
                        @error('land_area')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Features -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Features</h3>
                <div>
                    <label for="features" class="block text-sm font-medium text-gray-700">Features (comma separated)</label>
                    <textarea name="features" id="features" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('features') border-red-300 @enderror" placeholder="e.g., Air Conditioning, Balcony, Parking Space, Garden View">{{ old('features') }}</textarea>
                    <p class="mt-2 text-sm text-gray-500">Enter features separated by commas</p>
                    @error('features')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Floor Plans & Images -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Floor Plans & Images</h3>
                <div class="space-y-6">
                    <div>
                        <label for="floor_plan" class="block text-sm font-medium text-gray-700">Floor Plan</label>
                        <input type="file" name="floor_plan" id="floor_plan" accept="image/*,.pdf" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none focus:border-indigo-500 @error('floor_plan') border-red-300 @enderror">
                        <p class="mt-2 text-sm text-gray-500">Upload floor plan (PDF or image formats)</p>
                        @error('floor_plan')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="images" class="block text-sm font-medium text-gray-700">Unit Type Images</label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none focus:border-indigo-500 @error('images') border-red-300 @enderror">
                        <p class="mt-2 text-sm text-gray-500">Upload multiple images (JPG, PNG, etc.)</p>
                        @error('images')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('unit-types.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <i class="fas fa-save mr-2"></i> Create Unit Type
            </button>
        </div>
    </form>
</div>
@endsection