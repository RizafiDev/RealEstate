@extends('layouts.app')

@section('title', 'Add to Catalog')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Add Project to Catalog</h1>
        <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-arrow-left mr-2"></i> Back to Catalog
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <form action="{{ route('catalog.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Selection -->
                <div class="md:col-span-2">
                    <label for="project_id" class="block text-sm font-medium text-gray-700">Select Project</label>
                    <select name="project_id" id="project_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('project_id') border-red-300 @enderror">
                        <option value="">Choose a project</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }} 
                                data-description="{{ $project->description }}"
                                data-location="{{ $project->location->name }}"
                                data-developer="{{ $project->developer->name }}">
                                {{ $project->name }} - {{ $project->location->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Featured -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Featured Project</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="featured" value="1" {{ old('featured') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Mark as featured project</span>
                        </label>
                    </div>
                    @error('featured')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700">Display Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('sort_order') border-red-300 @enderror">
                    <p class="mt-1 text-sm text-gray-500">Lower numbers appear first (0 = first)</p>
                    @error('sort_order')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catalog Description Override -->
                <div class="md:col-span-2">
                    <label for="catalog_description" class="block text-sm font-medium text-gray-700">Catalog Description</label>
                    <textarea name="catalog_description" id="catalog_description" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('catalog_description') border-red-300 @enderror" placeholder="Optional: Override project description for catalog display">{{ old('catalog_description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Leave empty to use project's original description</p>
                    @error('catalog_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catalog Images -->
                <div class="md:col-span-2">
                    <label for="catalog_images" class="block text-sm font-medium text-gray-700">Catalog Images</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="catalog_images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Upload catalog images</span>
                                    <input id="catalog_images" name="catalog_images[]" type="file" class="sr-only" multiple accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB each</p>
                        </div>
                    </div>
                    @error('catalog_images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Visibility Settings -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Visibility</label>
                    <div class="mt-2 space-y-2">
                        <label class="inline-flex items-center">
                            <input type="radio" name="visibility" value="public" {{ old('visibility', 'public') == 'public' ? 'checked' : '' }} class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Public - Visible to all website visitors</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="visibility" value="private" {{ old('visibility') == 'private' ? 'checked' : '' }} class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Private - Only visible to logged-in users</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="visibility" value="hidden" {{ old('visibility') == 'hidden' ? 'checked' : '' }} class="text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-600">Hidden - Not visible in catalog</span>
                        </label>
                    </div>
                    @error('visibility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SEO Settings -->
                <div class="md:col-span-2 border-t pt-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h4>
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Title</label>
                            <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" maxlength="60" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_title') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Recommended: 50-60 characters</p>
                            @error('meta_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Description</label>
                            <textarea name="meta_description" id="meta_description" rows="3" maxlength="160" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('meta_description') border-red-300 @enderror">{{ old('meta_description') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Recommended: 150-160 characters</p>
                            @error('meta_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keywords -->
                        <div>
                            <label for="keywords" class="block text-sm font-medium text-gray-700">Keywords</label>
                            <input type="text" name="keywords" id="keywords" value="{{ old('keywords') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('keywords') border-red-300 @enderror">
                            <p class="mt-1 text-sm text-gray-500">Separate keywords with commas</p>
                            @error('keywords')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i> Add to Catalog
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-fill information when project is selected
document.getElementById('project_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    
    if (selectedOption.value) {
        // Auto-fill meta title if empty
        const metaTitleField = document.getElementById('meta_title');
        if (!metaTitleField.value) {
            metaTitleField.value = selectedOption.text;
        }
        
        // Auto-fill description if empty
        const descriptionField = document.getElementById('catalog_description');
        if (!descriptionField.value && selectedOption.dataset.description) {
            descriptionField.value = selectedOption.dataset.description;
        }
        
        // Auto-fill meta description if empty
        const metaDescField = document.getElementById('meta_description');
        if (!metaDescField.value && selectedOption.dataset.description) {
            const desc = selectedOption.dataset.description;
            metaDescField.value = desc.length > 160 ? desc.substring(0, 157) + '...' : desc;
        }
    }
});
</script>
@endsection
