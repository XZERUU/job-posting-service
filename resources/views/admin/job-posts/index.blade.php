@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Job Posts Management</h1>
                <p class="text-gray-600">Review and manage all job postings on the platform</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">← Back to Dashboard</a>
        </div>

        <!-- Job Posts Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">All Job Posts</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Title</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Employer</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Location</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Salary</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Posted</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($job_posts as $post)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $post->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $post->employer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $post->location ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($post->salary ?? 0, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm space-x-3">
                                <form action="{{ route('admin.job-posts.destroy', $post) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No job posts found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-200">
                {{ $job_posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
