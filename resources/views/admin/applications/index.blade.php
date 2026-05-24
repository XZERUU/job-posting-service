@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Applications Management</h1>
                <p class="text-gray-600">Review all job applications submitted by job seekers</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">← Back to Dashboard</a>
        </div>

        <!-- Applications Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">All Applications</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Applicant</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Job Post</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Applied On</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $application->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $application->jobPost->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($application->status === 'approved') bg-green-100 text-green-800
                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($application->status ?? 'pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $application->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="#" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No applications found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-200">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
