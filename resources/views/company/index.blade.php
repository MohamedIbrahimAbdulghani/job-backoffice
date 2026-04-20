<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Company') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4 lg:p-6">
        {{-- To Show Success Message --}}
        <x-toast-notification />

        {{-- Buttons --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-end sm:space-y-0 sm:space-x-2">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('company.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Active Company</a>
            @else
                <a href="{{ route('company.index', ['archived' => 'true']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Archived Companies</a>
            @endif
            <a href="{{ route('company.create') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Add Company</a>
        </div>

        {{-- Table: tablet & desktop --}}
        <div class="hidden mt-4 md:block">
            <table class="w-full bg-white divide-y divide-gray-200 rounded-lg shadow table-fixed">
                <thead>
                    <tr>
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Name</th>
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Address</th>
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Industry</th>
                        <th class="hidden w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600 lg:table-cell">Website</th>
                        <th class="w-1/4 px-4 py-3 text-sm font-semibold text-left text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $company)
                        <tr class="border-b">
                            <td class="px-4 py-3 truncate lg:px-6 lg:py-4">
                                @if(request()->input('archived') == 'true')
                                    <span class="text-gray-500 truncate">{{ $company->name }}</span>
                                @else
                                    <a href="{{ route('company.show', $company->id) }}" class="text-blue-500 underline truncate hover:text-blue-700">{{ $company->name }}</a>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate lg:px-6 lg:py-4 lg:text-base">{{ $company->address }}</td>
                            <td class="px-4 py-3 text-sm text-gray-800 truncate lg:px-6 lg:py-4 lg:text-base">{{ $company->industry }}</td>
                            <td class="hidden px-4 py-3 text-sm text-gray-800 truncate lg:table-cell lg:px-6 lg:py-4 lg:text-base">{{ $company->website }}</td>
                            <td class="px-4 py-3 lg:px-6 lg:py-4">
                                <div class="flex space-x-3">
                                    @if(request()->input('archived') == 'true')
                                        <form action="{{ route('company.restore', $company->id) }}" method="post" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('company.edit', $company->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                                        <form action="{{ route('company.destroy', $company->id) }}" method="post" class="inline-block">
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
                            <td colspan="5" class="px-6 py-4 text-gray-800">No Categories Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards: mobile only --}}
        <div class="mt-4 space-y-3 md:hidden">
            @forelse ($companies as $company)
                <div class="p-4 bg-white rounded-lg shadow">
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Name</span>
                        <p class="text-gray-800 truncate">
                            @if(request()->input('archived') == 'true')
                                <span class="text-gray-500">{{ $company->name }}</span>
                            @else
                                <a href="{{ route('company.show', $company->id) }}" class="text-blue-500 underline hover:text-blue-700">{{ $company->name }}</a>
                            @endif
                        </p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Address</span>
                        <p class="text-gray-800 truncate">{{ $company->address }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Industry</span>
                        <p class="text-gray-800 truncate">{{ $company->industry }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Website</span>
                        <p class="text-gray-800 truncate">{{ $company->website }}</p>
                    </div>
                    <div class="flex pt-2 mt-2 space-x-4 border-t border-gray-100">
                        @if(request()->input('archived') == 'true')
                            <form action="{{ route('company.restore', $company->id) }}" method="post" class="inline-block">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                            </form>
                        @else
                            <a href="{{ route('company.edit', $company->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                            <form action="{{ route('company.destroy', $company->id) }}" method="post" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="flex text-red-500 hover:text-red-700">🗃️ Archive</button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <p class="px-4 py-4 text-gray-800">No Categories Found!</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $companies->links() }}
        </div>

    </div>
</x-app-layout>
