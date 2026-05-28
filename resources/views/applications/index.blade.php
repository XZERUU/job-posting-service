<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">My Applications</h1>

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Job Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Company</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($applications as $application)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $application->jobPost->job_title ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $application->jobPost->employer->name ?? 'Employer' }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <span class="rounded-full px-3 py-1 text-xs font-medium
                                    @if($application->status === 'approved') bg-green-50 text-green-700
                                    @elseif($application->status === 'rejected') bg-red-50 text-red-700
                                    @else bg-yellow-50 text-yellow-700
                                    @endif">
                                    {{ $application->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if(in_array($application->status, ['pending', 'for_review'], true))
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('applications.edit', $application) }}" class="text-blue-600 hover:text-blue-900 font-medium">Edit</a>
                                        <form method="POST" action="{{ route('applications.destroy', $application) }}" onsubmit="return confirm('Cancel this application?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium">Cancel</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-gray-400">Locked</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-sm">
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
