<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Users') }} {{ request()->input('archived') == 'true' ? '(Archived)' : '' }}
        </h2>
    </x-slot>

    <div class="p-3 sm:p-4 lg:p-6">
        {{-- To Show Success Message --}}
        <x-toast-notification />

        {{-- Buttons --}}
        <div class="flex flex-col space-y-2 sm:flex-row sm:items-center sm:justify-end sm:space-y-0 sm:space-x-2">
            @if(request()->input('archived') == 'true')
                <a href="{{ route('user.index') }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Active Users</a>
            @else
                <a href="{{ route('user.index', ['archived' => 'true']) }}" class="inline-flex items-center justify-center w-full px-4 py-2 text-white bg-black rounded-md hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:w-auto">Archived Users</a>
            @endif
        </div>

        {{-- Table: tablet & desktop --}}
        <div class="hidden mt-4 md:block">
            <table class="w-full bg-white divide-y divide-gray-200 rounded-lg shadow table-fixed">
                <thead>
                    <tr>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600">Name</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600 ">Email</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600 ">Role</th>
                        <th class="px-4 py-3 text-sm font-semibold text-left text-gray-600 ">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-3 text-gray-800 truncate">{{ $user->name}} </td>
                            <td class="px-4 py-3 text-gray-800 truncate">{{ $user->email}}</td>
                            <td class="px-4 py-3 text-gray-800 truncate">{{ $user->role }}</td>
                            <td class="px-4 py-3">
                                <div class="flex space-x-4">
                                    @if(request()->input('archived') == 'true')
                                        <form action="{{ route('user.restore', $user->id) }}" method="post" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                                        </form>
                                    @else
                                        <a href="{{ route('user.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                                        <form action="{{ route('user.destroy', $user->id) }}" method="post" class="inline-block">
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
                            <td colspan="5" class="px-6 py-4 text-gray-800">No Users Found!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards: mobile only --}}
        <div class="mt-4 space-y-3 md:hidden">
            @forelse ($users as $user)
                <div class="p-4 bg-white rounded-lg shadow">
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Name</span>
                        <p class="text-gray-800 truncate">{{ $user->name }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Email</span>
                        <p class="text-gray-800 truncate">{{ $user->email }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Role</span>
                        <p class="text-gray-800 truncate">{{ $user->role }}</p>
                    </div>
                    <div class="mb-2">
                        <span class="text-xs font-semibold text-gray-500">Actions</span>
                            <div class="flex pt-2 mt-2 space-x-4 border-t border-gray-100">
                            @if(request()->input('archived') == 'true')
                                <form action="{{ route('user.restore', $user->id) }}" method="post" class="inline-block">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="flex text-red-500 hover:text-red-700">🔄 Restore</button>
                                </form>
                            @else
                                <a href="{{ route('user.edit', $user->id) }}" class="text-blue-500 hover:text-blue-700">✍🏼 Edit</a>
                                <form action="{{ route('user.destroy', $user->id) }}" method="post" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="flex text-red-500 hover:text-red-700">🗃️ Archive</button>
                                </form>
                            @endif
                            </div>
                    </div>
                </div>
            @empty
                <p class="px-4 py-4 text-gray-800">No Users Found!</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $users->links() }}
        </div>

    </div>
</x-app-layout>
