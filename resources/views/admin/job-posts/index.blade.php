@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Job Posts Management</h1>
                <p class="text-gray-600">Review and manage all job postings before they appear to job seekers</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Back to Dashboard</a>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

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
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Posted</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($job_posts as $post)
                            <tr class="border-t border-gray-200 hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $post->job_title ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $post->employer->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $post->location ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($post->salary_min || $post->salary_max)
                                        PHP {{ number_format($post->salary_min ?? 0) }} - {{ number_format($post->salary_max ?? 0) }}
                                    @else
                                        Competitive
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium
                                        @if($post->status === 'active') bg-green-100 text-green-800
                                        @elseif($post->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ $post->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $post->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="flex flex-wrap items-center gap-3">
                                        @if($post->status !== 'active')
                                            <form action="{{ route('admin.job-posts.approve', $post) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-green-700 hover:text-green-900 font-medium">Approve</button>
                                            </form>
                                        @endif

                                        @if($post->status !== 'rejected')
                                            <form action="{{ route('admin.job-posts.reject', $post) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-yellow-700 hover:text-yellow-900 font-medium">Reject</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.job-posts.destroy', $post) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-gray-500">No job posts found</td>
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
