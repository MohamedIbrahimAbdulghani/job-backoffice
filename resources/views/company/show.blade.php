<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 truncate">
            {{ $company->name }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4 lg:p-6">
        {{-- Back Button --}}
        @if(Auth::user()->role === 'admin')
            <div class="mb-6">
                <a href="{{ route('company.index') }}" class="px-4 py-2 text-white bg-gray-400 rounded-md hover:bg-gray-500">← Back</a>
            </div>
        @endif

        {{-- To Show Success Message --}}
        <x-toast-notification />

        <div class="w-full p-4 mx-auto bg-white rounded-lg shadow sm:p-6">
            {{-- Company Details --}}
            <div class="mb-4">
                <h3 class="text-lg font-bold">Company Information</h3>
                <p class="truncate"><strong>Name: </strong>{{ $company->name }}</p>
                <p class="truncate"><strong>Email: </strong>{{ $company->owner->email }}</p>
                <p class="truncate"><strong>Owner: </strong>{{ $company->owner->name }}</p>
                <p class="truncate"><strong>Address: </strong>{{ $company->address }}</p>
                <p class="truncate"><strong>Industry: </strong>{{ $company->industry }}</p>
                <p class="truncate"><strong>Website: </strong><a href="{{ $company->website }}" target="_blank" class="text-blue-500 underline hover:text-blue-700">{{ $company->website }}</a></p>
            </div>

            {{-- Edit And Archived Buttons --}}
            <div class="flex flex-col mb-6 space-y-2 sm:flex-row sm:justify-end sm:space-y-0 sm:space-x-2">
                @if (Auth::user()->role === 'company_owner')
                    <a href="{{ route('my-company.edit') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 sm:w-auto">Edit</a>
                @else
                    <a href="{{ route('company.edit', ['company' => $company->id, 'redirectToList' => 'false']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 sm:w-auto">Edit</a>
                @endif
                @if (Auth::user()->role == 'admin')
                    <form action="{{ route('company.destroy', $company->id) }}" method="post" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 sm:w-auto">Archive</button>
                    </form>
                @endif
            </div>

            @if (Auth::user()->role === 'admin')
            <!-- Tabs Navigation -->
            <div class="mb-6">
                <ul class="flex space-x-2">
                    <li>
                        <a href="{{ route('company.show', ['company' => $company->id, 'tab' => 'jobs']) }}" class="px-4 py-2 font-semibold text-gray-800 {{ request('tab') == 'jobs' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Jobs</a>
                    </li>
                    <li>
                        <a href="{{ route('company.show', ['company' => $company->id, 'tab' => 'applications']) }}" class="px-4 py-2 font-semibold text-gray-800 {{ request('tab') == 'applications' ? 'border-b-2 border-blue-500' : '' }}">Applications</a>
                    </li>
                </ul>
            </div>
            {{-- Tabs Content --}}
            <div>
                {{-- Jobs Tab --}}
                <div class="{{ request('tab') == 'jobs' || request('tab') == '' ? 'block' : 'hidden' }}">
                    {{-- Jobs Table: tablet & desktop --}}
                    <div class="hidden md:block">
                        <table class="w-full rounded-lg shadow table-fixed bg-gray-50">
                            <thead>
                                <tr>
                                    <th class="w-1/3 px-4 py-2 text-left bg-gray-100 rounded-tl-lg">Title</th>
                                    <th class="w-1/4 px-4 py-2 text-left bg-gray-100">Type</th>
                                    <th class="w-1/4 px-4 py-2 text-left bg-gray-100">Location</th>
                                    <th class="w-1/6 px-4 py-2 text-left bg-gray-100 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->Jobvacancy as $jobvacancy)
                                    <tr>
                                        <td class="px-4 py-2 truncate">{{ $jobvacancy->title }}</td>
                                        <td class="px-4 py-2 truncate">{{ $jobvacancy->type }}</td>
                                        <td class="px-4 py-2 truncate">{{ $jobvacancy->location }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('job_vacancy.show', $jobvacancy->id) }}" class="text-blue-500 underline hover:text-blue-700">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Jobs Cards: mobile only --}}
                    <div class="space-y-3 md:hidden">
                        @foreach ($company->Jobvacancy as $jobvacancy)
                            <div class="p-4 rounded-lg shadow bg-gray-50">
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Title</span>
                                    <p class="text-gray-800 truncate">{{ $jobvacancy->title }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Type</span>
                                    <p class="text-gray-800 truncate">{{ $jobvacancy->type }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="text-xs font-semibold text-gray-500">Location</span>
                                    <p class="text-gray-800 truncate">{{ $jobvacancy->location }}</p>
                                </div>
                                <div class="pt-2 border-t border-gray-200">
                                    <a href="{{ route('job_vacancy.show', $jobvacancy->id) }}" class="text-blue-500 underline hover:text-blue-700">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Applications Tab --}}
                <div class="{{ request('tab') == 'applications' ? 'block' : 'hidden' }}">

                    {{-- Applications Table: tablet & desktop --}}
                    <div class="hidden md:block">
                        <table class="w-full rounded-lg shadow table-fixed bg-gray-50">
                            <thead>
                                <tr>
                                    <th class="w-1/4 px-4 py-2 text-left bg-gray-100 rounded-tl-lg">Application Name</th>
                                    <th class="w-1/3 px-4 py-2 text-left bg-gray-100">Job Title</th>
                                    <th class="w-1/5 px-4 py-2 text-left bg-gray-100">Status</th>
                                    <th class="w-1/6 px-4 py-2 text-left bg-gray-100 rounded-tr-lg">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($company->jobApplications as $application)
                                    <tr>
                                        <td class="px-4 py-2 truncate">{{ $application->user->name }}</td>
                                        <td class="px-4 py-2 truncate">{{ $application->Jobvacancy->title }}</td>
                                        <td class="px-4 py-2 truncate">{{ $application->status }}</td>
                                        <td class="px-4 py-2">
                                            <a href="{{ route('job_vacancy.show', $application->id) }}" class="text-blue-500 underline hover:text-blue-700">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Applications Cards: mobile only --}}
                    <div class="space-y-3 md:hidden">
                        @foreach ($company->jobApplications as $application)
                            <div class="p-4 rounded-lg shadow bg-gray-50">
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Application Name</span>
                                    <p class="text-gray-800 truncate">{{ $application->user->name }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Job Title</span>
                                    <p class="text-gray-800 truncate">{{ $application->Jobvacancy->title }}</p>
                                </div>
                                <div class="mb-2">
                                    <span class="text-xs font-semibold text-gray-500">Status</span>
                                    <p class="text-gray-800 truncate">{{ $application->status }}</p>
                                </div>
                                <div class="pt-2 border-t border-gray-200">
                                    <a href="{{ route('job_vacancy.show', $application->id) }}" class="text-blue-500 underline hover:text-blue-700">View</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</x-app-layout>
