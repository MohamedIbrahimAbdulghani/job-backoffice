<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Job Vacancy') }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="w-full max-w-2xl p-4 mx-auto bg-white rounded-lg shadow-md sm:p-6">
            <form action="{{ route('job_vacancy.update', ['job_vacancy' => $job_vacancy->id, 'redirectToList' => request()->query('redirectToList')]) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- Job Vacancy Details --}}
                <div class="p-4 mb-4 border border-gray-100 rounded-lg shadow-sm sm:p-6 bg-gray-50">
                    <h3 class="text-lg font-bold">Job Vacancy Details</h3>
                    <p class="mb-2 text-sm text-gray-500">Please fill in the details of the Job Vacancy</p>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title" class="{{ $errors->has('title') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('title', $job_vacancy->title) }}">
                        @error('title')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" name="location" id="location" class="{{ $errors->has('location') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('location', $job_vacancy->location) }}">
                        @error('location')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="salary" class="block text-sm font-medium text-gray-700">Expected Salary (USD)</label>
                        <input type="number" name="salary" id="salary" class="{{ $errors->has('salary') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{old('salary',$job_vacancy->salary)}}">
                        @error('salary')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                        <select name="type" id="type" class="{{ $errors->has('type') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Type</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ old('type', $job_vacancy->type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="company" class="block text-sm font-medium text-gray-700">Company</label>
                        <select name="company" id="company" class="{{ $errors->has('company') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Company</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company', $job_vacancy->company_id ?? '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="job_category" class="block text-sm font-medium text-gray-700">Job Category</label>
                        <select name="job_category" id="job_category" class="{{ $errors->has('job_category') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select Job Category</option>
                            @foreach($job_categories as $job_category)
                                <option value="{{ $job_category->id }}"  {{ old('job_category', $job_vacancy->category_id ?? '') == $job_category->id ? 'selected' : '' }}>{{ $job_category->name }}</option>
                            @endforeach
                        </select>
                        @error('job_category')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea rows="3" name="description" id="description" class="{{ $errors->has('description') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $job_vacancy->description) }}</textarea>
                        @error('description')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('job_vacancy.index') }}" class="w-full px-4 py-2 text-center text-gray-500 rounded-md sm:w-auto hover:text-gray-700">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto">Update Job Vacancy</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
