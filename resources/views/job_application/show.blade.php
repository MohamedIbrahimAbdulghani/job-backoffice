<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $job_application->user->name }} | Applied to {{ $job_application->jobVacancy->title }}
        </h2>
    </x-slot>

    <div class="p-3 overflow-x-hidden sm:p-4 lg:p-6">
        {{-- Back Button --}}
        <div class="mt-2 mb-6">
            <a href="{{ route('job_application.index') }}" class="px-4 py-2 text-white bg-gray-400 rounded-md hover:bg-gray-500">← Back</a>
        </div>

        {{-- To Show Success Message --}}
        <x-toast-notification />

        <div class="w-full p-4 mx-auto bg-white rounded-lg shadow sm:p-6">
            {{-- Company Details --}}
            <div class="mb-4">
                <h3 class="text-lg font-bold">Company Information</h3>
                <p class="truncate"><strong>Applicant: </strong>{{ $job_application->user->name }}</p>
                <p class="truncate"><strong>Position: </strong>{{ $job_application->jobVacancy->title }}</p>
                <p class="truncate"><strong>Company: </strong>{{ $job_application->jobVacancy->company->name }}</p>
                <p class="truncate"><strong>Status: </strong><span class=" @if($job_application->status === 'pending') text-blue-500 @elseif($job_application->status === 'accepted') text-green-500 @else text-red-500 @endif ">{{ $job_application->status }}</span></p>
                <p class="truncate"><strong>Resume: </strong><a href="{{ $job_application->resume->filename }}" class="text-blue-500 underline hover:text-blue-700" target="_black">{{ $job_application->resume->filename }}</a></p>
            </div>

            {{-- Edit And Archived Buttons --}}
            <div class="flex flex-col mb-6 space-y-2 sm:flex-row sm:justify-end sm:space-y-0 sm:space-x-2">
                <a href="{{ route('job_application.edit', ['job_application' => $job_application->id, 'redirectToList' => 'false']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 sm:w-auto">Edit</a>
                <form action="{{ route('job_application.destroy', $job_application->id) }}" method="post" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 sm:w-auto">Archive</button>
                </form>
            </div>

            <!-- Tabs Navigation -->
            <div class="mb-6">
                <ul class="flex space-x-2">
                    <li>
                        <a href="{{ route('job_application.show', ['job_application' => $job_application->id, 'tab' => 'resume']) }}" class="px-4 py-2 font-semibold text-gray-800 {{ request('tab') == 'resume' || request('tab') == '' ? 'border-b-2 border-blue-500' : '' }}">Resume</a>
                    </li>
                    <li>
                        <a href="{{ route('job_application.show', ['job_application' => $job_application->id, 'tab' => 'aiFeedback']) }}" class="px-4 py-2 font-semibold text-gray-800 {{ request('tab') == 'aiFeedback' ? 'border-b-2 border-blue-500' : '' }}">aiFeedback</a>
                    </li>
                </ul>
            </div>

            {{-- Tabs Content --}}
            <div>
                {{-- Resume Tab --}}
                <div class="{{ request('tab') == 'resume' || request('tab') == '' ? 'block' : 'hidden' }}">

                    {{-- Jobs Resume: tablet & desktop --}}
                    <div class="hidden overflow-x-auto md:block ">
                        <table class="w-full rounded-lg shadow bg-gray-50 " >
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left bg-gray-100 rounded-tl-lg ">Summary</th>
                                    <th class="px-4 py-2 text-left bg-gray-100 ">Skills</th>
                                    <th class="px-4 py-2 text-left bg-gray-100 ">Experience</th>
                                    <th class="px-4 py-2 text-left bg-gray-100 rounded-tr-lg ">Education</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td class="px-4 py-2 align-top ">{{ $job_application->resume->summary }}</td>
                                        <td class="px-4 py-2 align-top ">{{ $job_application->resume->skills }}</td>
                                        <td class="px-4 py-2 align-top ">{{ $job_application->resume->experience }}</td>
                                        <td class="px-4 py-2 align-top ">{{ $job_application->resume->education }}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Resume Cards: mobile only --}}
                    <div class="space-y-3 md:hidden ">
                            <div class="p-4 rounded-lg shadow bg-gray-50">
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Summary</span>
                                    <p class="p-2 text-gray-800 border-b">{{ $job_application->resume->summary }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Skills</span>
                                    <p class="p-2 text-gray-800 border-b">{{ $job_application->resume->skills }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Experience</span>
                                    <p class="p-2 text-gray-800 border-b">{{ $job_application->resume->experience }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Education</span>
                                    <p class="p-2 text-gray-800 border-b">{{ $job_application->resume->education }}</p>
                                </div>
                            </div>
                    </div>
                </div>

                {{-- aiFeedback Tab --}}
                <div class="{{ request('tab') == 'aiFeedback' ? 'block' : 'hidden' }}">
                    {{-- aiFeedback Table: tablet & desktop --}}
                    <div class="hidden overflow-x-auto md:block">
                        <table class="w-full rounded-lg shadow bg-gray-50">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left bg-gray-100 rounded-tl-lg">Ai Score</th>
                                    <th class="px-4 py-2 text-left bg-gray-100 rounded-tr-lg">Feedback</th>
                                </tr>
                            </thead>
                            <tbody>
                                    <tr>
                                        <td class="px-4 py-2 align-top ">{{ $job_application->aiGeneratedScore }}</td>
                                        <td class="px-4 py-2 align-top ">{{ $job_application->aiGeneratedFeedback }}</td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- aiFeedback Cards: mobile only --}}
                    <div class="space-y-3 md:hidden">
                            <div class="p-4 rounded-lg shadow bg-gray-50">
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Ai Score</span>
                                    <p class="p-2 text-gray-800 border-b">{{ $job_application->aiGeneratedScore }}</p>
                                </div>
                                <div class="mb-1">
                                    <span class="text-xs font-semibold text-gray-500">Feedback</span>
                                    <p class="p-2 text-gray-800 border-b">{{ $job_application->aiGeneratedFeedback }}</p>
                                </div>
                            </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
