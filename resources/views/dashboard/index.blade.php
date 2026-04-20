<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard Analytics') }}
        </h2>
    </x-slot>

    <div class="flex flex-col gap-4 px-6 py-12">
        {{-- Overview Cards --}}
        <div class="grid gap-4 gird-cols-1 md:grid-cols-3">
            {{-- Total Jobs --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Active Users</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $analytics['activeUsers'] }}</p>
                    <p class="text-sm text-gray-500">Last 30 days</p>
                </div>
            </div>
            {{-- Active Job Postings --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Active Job Postings</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $analytics['totalJobs'] }}</p>
                    <p class="text-sm text-gray-500">Currently active</p>
                </div>
            </div>
            {{-- Total Applications --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Total Applications</h3>
                    <p class="text-3xl font-bold text-indigo-600">{{ $analytics['totalApplications'] }}</p>
                    <p class="text-sm text-gray-500">All time</p>
                </div>
            </div>
        </div>

        {{-- Most Applied Jobs --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium text-gray-900">Most Applied Jobs</h2>
                    <div>
                        <table class="w-full mt-3 text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 font-medium">#</th>
                                    <th class="px-6 py-3 font-medium">Job Title</th>
                                    @if (Auth::user()->role === 'admin')
                                        <th class="px-6 py-3 font-medium">Company</th>
                                    @endif
                                    <th class="px-6 py-3 font-medium">Applications</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $x=1; ?>
                                @foreach ($analytics['mostAppliedJobs'] as $job)
                                <tr class="transition-colors hover:bg-gray-50">
                                    <td class="px-6 py-4 text-gray-400">{{ $x++ }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $job->title }}</td>
                                    @if (Auth::user()->role === 'admin')
                                        <td class="px-6 py-4 text-gray-500">{{ $job->company->name }}</td>
                                    @endif
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">{{ $job->totalCountJobApplication }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        {{-- Conversion Rates --}}
            <div class="overflow-hidden bg-white rounded-lg shadow-sm ">
                <div class="p-6 text-gray-900">
                    <h2 class="text-lg font-medium text-gray-900">Top Converting Job Posts</h2>
                    <div>
                        <table class="w-full mt-3 text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 font-medium">#</th>
                                    <th class="px-6 py-3 font-medium">Job Title</th>
                                    <th class="px-6 py-3 font-medium">Views</th>
                                    <th class="px-6 py-3 font-medium">Applications</th>
                                    <th class="px-6 py-3 font-medium">Conversion Rate</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php $x=1; ?>
                                @foreach ($analytics['conversionJobRates'] as $conversionJobRate)
                                    <tr class="transition-colors hover:bg-gray-50">
                                        <td class="px-6 py-4 text-gray-400">{{ $x++ }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $conversionJobRate->title }}</td>
                                        <td class="px-6 py-4 text-gray-500">{{ $conversionJobRate->view_count }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full">{{ $conversionJobRate->totalCountJobApplication }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500">{{ $conversionJobRate->conversionRate }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</x-app-layout>
