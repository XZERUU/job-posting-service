<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Employer Dashboard</h1>
            <a href="{{ route('jobs.create') }}" class="px-4 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800">
                + Post New Job
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                <h3 class="text-sm text-gray-500">Active Jobs</h3>
                <p class="text-2xl font-bold text-green-700">{{ $jobs->count() }}</p>
            </div>
            </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900">Your Recent Job Postings</h2>
            </div>
            <table class="w-full text-left">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Title</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($jobs as $job)
                    <tr>
                        <td class="px-6 py-4">{{ $job->title }}</td>
                        <td class="px-6 py-4 text-green-600">Active</td>
                        <td class="px-6 py-4">
                            <a href="#" class="text-green-700 hover:underline">View Applicants</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">No jobs posted yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>