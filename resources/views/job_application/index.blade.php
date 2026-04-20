<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Job Application') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4 lg:p-6">
        {{-- To Show Success Message --}}
        <x-toast-notification />

        {{-- Buttons --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-end sm:space-y-0 sm:space-x-2">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('job_application.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Active Job Application</a>
            @else
                <a href="{{ route('job_application.index', ['archived' => 'true']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Archived Job Application</a>
            @endif
        </div>

        {{-- Table: tablet & desktop --}}
        <div class="hidden mt-4 md:block">
            <table class="w-full bg-white divide-y divide-gray-200 rounded-lg shadow table-fixed">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Application Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600 ">Position(Job Vacancy)</th>
                        @if (Auth::user()->role === 'admin')
                            <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600 ">Company</th>
                        @endif
                        <th class="hidden px-4 py-3 text-sm font-semibold text-left text-gray-600 lg:table-cell">Status</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600 ">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($job_applications as $job_application)
                        <tr class="border-b">
                            <td class="px-4 py-3 truncate">
                                @if(request()->input('archived') == 'true')
                                    <span class="text-gray-500 truncate">{{ $job_application->user->name }}</span>
                                @else
                                    <a href="{{ route('job_application.show', $job_application->id) }}" class="text-blue-500 underline hover:text-blue-700">{{ $job_application->user->name }}</a>
                                @endif
                                </td>
                            <td class="px-4 py-3 text-gray-800 truncate">{{ $job_application->jobVacancy->title ?? 'NULL'}}</td>
                            @if (Auth::user()->role === 'admin')
                                <td class="px-4 py-3 text-gray-800 truncate">{{ $job_application->jobVacancy->company->name ?? 'NULL' }}</td>
                            @endif

                            {{-- @if($job_application->status  === 'pending')
                                <td class="hidden px-4 py-3 text-blue-500 truncate lg:table-cell">{{ $job_application->status }}</td>
                            @elseif($job_application->status  === 'accepted')
                                <td class="hidden px-4 py-3 text-green-500 truncate lg:table-cell">{{ $job_application->status }}</td>
                            @else
                                <td class="hidden px-4 py-3 text-red-500 truncate lg:table-cell">{{ $job_application->status }}</td>
                            @endif --}}

                            <td class="px-4 py-3 @if($job_application->status  === 'pending') text-blue-500  @elseif($job_application->status  === 'accepted') text-green-500 @else text-red-500 @endif truncate">{{ $job_application->status }}</td>

                            <td class="px-4 py-3">
                                <div class="flex space-x-4">
                                    @if(request()->input('archived') == 'true')
                                        <form action="{{ route('job_application.restore', $job_application->id) }}" method="post" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('job_application.edit', $job_application->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                                        <form action="{{ route('job_application.destroy', $job_application->id) }}" method="post" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex text-red-500 hover:text-red-700">🗃️ Archive</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-gray-800">No Job Vacancy Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards: mobile only --}}
        <div class="mt-4 space-y-3 md:hidden">
            @forelse ($job_applications as $job_application)
                <div class="p-4 bg-white rounded-lg shadow">
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Application Name</span>
                        <p class="truncate">
                            <a href="{{ route('job_application.show', $job_application->id) }}" class="text-blue-500 underline hover:text-blue-700">{{ $job_application->user->name }}</a>
                        </p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Position(Job Vacancy)</span>
                        <p class="text-gray-800 truncate">{{ $job_application->jobVacancy->title ?? 'NULL'}}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Company</span>
                        <p class="text-gray-800 truncate">{{ $job_application->jobVacancy->company->name ?? 'NULL' }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Status</span>
                        <p class="@if($job_application->status  === 'pending') text-blue-500  @elseif($job_application->status  === 'accepted') text-green-500 @else text-red-500 @endif truncate">{{ $job_application->status }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Actions</span>
                            <div class="flex pt-2 mt-2 space-x-4 border-t border-gray-100">
                            @if(request()->input('archived') == 'true')
                                <form action="{{ route('job_application.restore', $job_application->id) }}" method="post" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                                </form>
                            @else
                                <a href="{{ route('job_application.edit', $job_application->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                                <form action="{{ route('job_application.destroy', $job_application->id) }}" method="post" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex text-red-500 hover:text-red-700">🗃️ Archive</button>
                                </form>
                            @endif
                            </div>
                    </div>
                </div>
            @empty
                <p class="px-4 py-4 text-gray-800">No Job Application Found!</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $job_applications->links() }}
        </div>

    </div>
</x-app-layout>
