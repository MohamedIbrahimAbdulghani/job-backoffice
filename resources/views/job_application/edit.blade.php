<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Application Status') }} | {{ $job_application->user->name }}
        </h2>
    </x-slot>

    <div class="p-4 sm:p-6">
        <div class="w-full max-w-2xl p-4 mx-auto bg-white rounded-lg shadow-md sm:p-6">
            <form action="{{ route('job_application.update', ['job_application' => $job_application->id, 'redirectToList' => request()->query('redirectToList')]) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- Job Application Details --}}
                <div class="p-4 mb-4 border border-gray-100 rounded-lg shadow-sm sm:p-6 bg-gray-50">
                    <h3 class="text-lg font-bold">Job Application Details</h3>
                    <p class="mb-2 text-sm text-gray-500">Enter the job application details</p>

                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700">Application Name</label>
                        <span>{{ $job_application->user->name }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Job Vacancy</label>
                        <span>{{ $job_application->jobVacancy->title }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Company</label>
                        <span>{{ $job_application->jobVacancy->company->name }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Ai Generated Score</label>
                        <span>{{ $job_application->aiGeneratedScore }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Ai Generated Feedback</label>
                        <span>{{ $job_application->aiGeneratedFeedback }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" class="{{ $errors->has('status') ? 'outline-red-500 outline outline-1' : '' }} block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="pending" {{ old('status', $job_application->status) == 'pending' ? 'selected' : '' }}>Pending - Under Review</option>
                            <option value="rejected" {{ old('status', $job_application->status) == 'rejected' ? 'selected' : '' }}>Rejected - Disqualified</option>
                            <option value="accepted" {{ old('status', $job_application->status) == 'accepted' ? 'selected' : '' }}>Accepted - Qualified</option>
                        </select>
                        @error('status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                    <a href="{{ route('job_application.index') }}" class="w-full px-4 py-2 text-center text-gray-500 rounded-md sm:w-auto hover:text-gray-700">Cancel</a>
                    <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto">Update Application Status</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>
