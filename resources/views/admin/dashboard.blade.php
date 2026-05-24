@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Admin Dashboard</h1>
            <p class="text-gray-600">Welcome back! Here's an overview of your platform.</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Users</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292m0 0H8.646m3.354 0H16m-4-8a4 4 0 100 8 4 4 0 000-8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Employers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_employers'] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5.581m0 0H9m5.581 0a2 2 0 100-4 2 2 0 000 4zm0 0a2 2 0 110-4 2 2 0 010 4z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Job Seekers</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_seekers'] }}</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Job Posts</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_job_posts'] }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Applications</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
                    </div>
                    <div class="bg-red-100 rounded-full p-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <a href="{{ route('admin.users') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">Manage Users</h3>
                <p class="text-blue-100">View and manage all registered users</p>
            </a>
            <a href="{{ route('admin.job-posts') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">Manage Job Posts</h3>
                <p class="text-green-100">Review and manage job postings</p>
            </a>
            <a href="{{ route('admin.applications') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-6 text-white hover:shadow-lg transition">
                <h3 class="text-lg font-semibold mb-2">View Applications</h3>
                <p class="text-purple-100">Track all job applications</p>
            </a>
        </div>

        <!-- Recent Users -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Recent Users</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Joined</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_users as $user)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'employer') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
