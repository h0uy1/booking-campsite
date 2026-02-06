<x-layout>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Edit Tent</h1>
        <p class="mt-1 text-sm text-gray-500">Update the details of this tent type.</p>
    </div>

    <div class="max-w-3xl mx-auto">
        <form method="POST" action="/tents/{{ $tent->id }}/update"  class="space-y-6" enctype="multipart/form-data">
            @csrf
            
            <!-- Tent Details -->
            <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Configuration
                    </h3>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Image Upload -->
                        <div>
                            <label for="image" class="block text-sm font-medium text-gray-700">Tent Image</label>
                            
                            @if($tent->image)
                                <div class="mt-2 mb-4">
                                    <img src="{{ asset('storage/' . $tent->image) }}" alt="Current Image" class="h-32 w-auto object-cover rounded-md border border-gray-300">
                                    <p class="text-xs text-gray-500 mt-1">Current Image</p>
                                </div>
                            @endif

                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-green-500 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                            <span>Upload a new file</span>
                                            <input id="image" name="image" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PNG, JPG, GIF up to 2MB
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Image Preview -->
                            <div id="image-preview-container" class="mt-4 hidden">
                                <p class="block text-sm font-medium text-gray-700 mb-2">New Image Preview</p>
                                <img id="image-preview" src="#" alt="Image Preview" class="h-48 w-auto object-cover rounded-md border border-gray-300 shadow-sm">
                            </div>
                            @error('image')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Tent Type <span class="text-red-500">*</span></label>
                            <input type="text" name="type" id="type" required value="{{ old('type', $tent->type) }}"
                                class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3"
                                placeholder="e.g. Deluxe Safari Tent">
                        </div>

                        <!-- Capacity & Price -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity (People) <span class="text-red-500">*</span></label>
                                <input type="number" name="capacity" id="capacity" required min="1" value="{{ old('capacity', $tent->capacity) }}"
                                    class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3">
                            </div>

                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price per Night ($) <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">$</span>
                                    </div>
                                    <input type="number" name="price" id="price" required min="0" step="0.01" value="{{ old('price', $tent->price) }}"
                                        class="mt-1 focus:ring-green-500 focus:border-green-500 block w-full pl-7 shadow-sm sm:text-sm border-gray-300 rounded-md border py-2 px-3"
                                        placeholder="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end space-x-3">
                <a href="/tents" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Cancel
                </a>
                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Update Tent
                </button>
            </div>
        </form>
    </div>


</x-layout>
