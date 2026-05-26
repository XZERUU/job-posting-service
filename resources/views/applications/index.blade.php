<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">My Applications</h1>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Job Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($applications ?? [] as $application)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $application->job_title }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $application->company_name }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                @if(in_array($application->status, ['approved', 'hired'])) bg-green-100 text-green-800
                                @elseif(in_array($application->status, ['reviewing', 'under_review', 'interview'])) bg-amber-100 text-amber-800
                                @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ in_array($application->status, ['approved', 'hired']) ? 'Hired' : \Illuminate\Support\Str::headline($application->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500 text-sm">
                            You haven't submitted any applications yet.
                            <a href="{{ route('jobs.index') }}" class="text-green-700 font-medium hover:underline">Start browsing jobs!</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
