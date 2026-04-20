<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $job_vacancies->title }}
        </h2>
    </x-slot>

    <div class="p-3 overflow-x-auto sm:p-4 lg:p-6">
        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('job_vacancy.index') }}" class="px-4 py-2 text-white bg-gray-400 rounded-md hover:bg-gray-500">← Back</a>
        </div>

        {{-- To Show Success Message --}}
        <x-toast-notification />

        <div class="w-full p-4 mx-auto bg-white rounded-lg shadow sm:p-6">
            {{-- Job Vacancy Details --}}
            <div class="mb-4">
                <h3 class="text-lg font-bold">Job Vacancy Information</h3>
                <p><strong>Title: </strong>{{ $job_vacancies->title }}</p>
                <p><strong>Company Name: </strong>{{ $job_vacancies->company->name }}</p>
                <p><strong>Location: </strong>{{ $job_vacancies->location }}</p>
                <p><strong>Type: </strong>{{ $job_vacancies->type }}</p>
                <p><strong>Salary: </strong>${{ number_format($job_vacancies->salary, 2) }}</p>
                <p><strong>Description: </strong>{{ $job_vacancies->description }}</p>
            </div>

            {{-- Edit And Archived Buttons --}}
            <div class="flex flex-col mb-6 space-y-2 sm:flex-row sm:justify-end sm:space-y-0 sm:space-x-2">
                <a href="{{ route('job_vacancy.edit', ['job_vacancy' => $job_vacancies->id, 'redirectToList' => 'false']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 sm:w-auto">Edit</a>
                <form action="{{ route('job_vacancy.destroy', $job_vacancies->id) }}" method="post" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 sm:w-auto">Archive</button>
                </form>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-6">
                <ul class="flex space-x-2">
                    <li>
                        <a href="{{ route('job_vacancy.show', ['job_vacancy' => $job_vacancies->id, 'tab' => 'applications']) }}" class="px-4 py-2 font-semibold text-gray-800 {{ request('tab') == 'applications' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Applications</a>
                    </li>
                </ul>
            </div>

            {{-- Tabs Content --}}
            <div>
                {{-- Applications Tab --}}
                <div class="{{ request('tab') == 'applications' || request('tab') == '' ? 'block' : 'hidden' }}">

                    {{-- Table: tablet & desktop --}}
                    <table class="hidden min-w-full rounded-lg shadow bg-gray-50 md:table">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 bg-gray-100 rounded-tl-lg">Application Name</th>
                                <th class="px-4 py-2 bg-gray-100">Job Title</th>
                                <th class="px-4 py-2 bg-gray-100">Status</th>
                                <th class="px-4 py-2 bg-gray-100 rounded-tr-lg">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($job_vacancies->jobApplications as $application)
                                <tr>
                                    <td class="px-4 py-2 max-w-[150px] truncate">{{ $application->user->name }}</td>
                                    <td class="px-4 py-2 max-w-[150px] truncate">{{ $application->Jobvacancy->title }}</td>
                                    <td class="px-4 py-2">{{ $application->status }}</td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('job_vacancy.show', $application->id) }}" class="text-blue-500 underline hover:text-blue-700">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center">No Application Found! </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- Mobile Cards: mobile only --}}
                    <div class="space-y-3 md:hidden">
                        @foreach ($job_vacancies->jobApplications as $application)
                            <div class="p-4 rounded-lg shadow bg-gray-50">
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Application Name</span>
                                    <p class="text-gray-800 truncate max-w-[250px]">{{ $application->user->name }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Job Title</span>
                                    <p class="text-gray-800 truncate max-w-[250px]">{{ $application->Jobvacancy->title }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="text-xs font-semibold text-gray-500">Status</span>
                                    <p class="text-gray-800">{{ $application->status }}</p>
                                </div>
                                <div class="pt-2 border-t border-gray-200">
                                    <a href="{{ route('job_vacancy.show', $application->id) }}" class="text-blue-500 underline hover:text-blue-700">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>
