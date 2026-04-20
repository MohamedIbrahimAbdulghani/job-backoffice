<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Job Vacancy') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4 lg:p-6">
        {{-- To Show Success Message --}}
        <x-toast-notification />

        {{-- Buttons --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-end sm:space-y-0 sm:space-x-2">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('job_vacancy.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Active Job Vacancy</a>
            @else
                <a href="{{ route('job_vacancy.index', ['archived' => 'true']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Archived Job Vacancies</a>
            @endif
            <a href="{{ route('job_vacancy.create') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Add Job Vacancy</a>
        </div>

        {{-- Table: tablet & desktop --}}
        <div class="hidden mt-4 md:block">
            <table class="w-full bg-white divide-y divide-gray-200 rounded-lg shadow table-fixed">
                <thead>
                    <tr>
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Title</th>
                        @if(Auth::user()->role === 'admin')
                            <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Company</th>
                        @endif
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Location</th>
                        <th class="hidden w-1/6 px-4 py-3 text-sm font-semibold text-left text-gray-600 lg:table-cell">Type</th>
                        <th class="hidden w-1/6 px-4 py-3 text-sm font-semibold text-left text-gray-600 lg:table-cell">Salary</th>
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($job_vacancies as $job_vacancy)
                        <tr class="border-b">
                            <td class="px-4 py-3 truncate">
                                @if(request()->input('archived') == 'true')
                                    <span class="text-gray-500 truncate">{{ $job_vacancy->title }}</span>
                                @else
                                    <a href="{{ route('job_vacancy.show', $job_vacancy->id) }}" class="text-blue-500 underline hover:text-blue-700">{{ $job_vacancy->title }}</a>
                                @endif
                                </td>
                            @if(Auth::user()->role === 'admin')
                                <td class="px-4 py-3 text-gray-800 truncate">{{ $job_vacancy->company->name }}</td>
                            @endif
                            <td class="px-4 py-3 text-gray-800 truncate">{{ $job_vacancy->location }}</td>
                            <td class="hidden px-4 py-3 text-gray-800 truncate lg:table-cell">{{ $job_vacancy->type }}</td>
                            <td class="hidden px-4 py-3 text-gray-800 truncate lg:table-cell">${{ number_format($job_vacancy->salary, 2) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-4">
                                    @if(request()->input('archived') == 'true')
                                        <form action="{{ route('job_vacancy.restore', $job_vacancy->id) }}" method="post" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('job_vacancy.edit', $job_vacancy->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                                        <form action="{{ route('job_vacancy.destroy', $job_vacancy->id) }}" method="post" class="inline-block">
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
                            <td colspan="6" class="px-6 py-4 text-gray-800">No Job Vacancy Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards: mobile only --}}
        <div class="mt-4 space-y-3 md:hidden">
            @forelse ($job_vacancies as $job_vacancy)
                <div class="p-4 bg-white rounded-lg shadow">
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Title</span>
                        <p class="truncate">
                            <a href="{{ route('job_vacancy.show', $job_vacancy->id) }}" class="text-blue-500 underline hover:text-blue-700">{{ $job_vacancy->title }}</a>
                        </p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Company</span>
                        <p class="text-gray-800 truncate">{{ $job_vacancy->company->name }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Location</span>
                        <p class="text-gray-800 truncate">{{ $job_vacancy->location }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Type</span>
                        <p class="text-gray-800 truncate">{{ $job_vacancy->type }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Salary</span>
                        <p class="text-gray-800">${{ number_format($job_vacancy->salary, 2) }}</p>
                    </div>
                    <div class="flex pt-2 mt-2 space-x-4 border-t border-gray-100">
                        @if(request()->input('archived') == 'true')
                            <form action="{{ route('job_vacancy.restore', $job_vacancy->id) }}" method="post" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                            </form>
                        @else
                            <a href="{{ route('job_vacancy.edit', $job_vacancy->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                            <form action="{{ route('job_vacancy.destroy', $job_vacancy->id) }}" method="post" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex text-red-500 hover:text-red-700">🗃️ Archive</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="px-4 py-4 text-gray-800">No Job Vacancy Found!</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $job_vacancies->links() }}
        </div>

    </div>
</x-app-layout>
