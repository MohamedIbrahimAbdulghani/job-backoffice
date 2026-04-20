<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Job Category') }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4 lg:p-6">
        <div class="w-full p-4 mx-auto bg-white rounded-lg shadow-md sm:p-6 sm:max-w-xl lg:max-w-2xl">
            <form action="{{ route('job_category.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input type="text" name="name" id="name" class="{{ $errors->has('name') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base sm:text-sm" value="{{ old('name', $category->name) }}">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-col space-y-2 sm:flex-row sm:justify-end sm:space-y-0 sm:gap-4">
                    <a href="{{ route('job_category.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-gray-500 rounded-md hover:text-gray-700 sm:w-auto">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto">Update Category</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
