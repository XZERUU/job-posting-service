@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
                <p class="text-gray-600">Manage all registered users on the platform</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">← Back to Dashboard</a>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">All Users</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Role</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Verified</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Joined</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="border-t border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-medium
                                    @if($user->role === 'admin') bg-red-100 text-red-800
                                    @elseif($user->role === 'employer') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800
                                    @endif">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($user->email_verified_at)
                                    <span class="text-green-600 font-medium">✓ Verified</span>
                                @else
                                    <span class="text-gray-400">Not verified</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm space-x-3">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-blue-600 hover:text-blue-900 font-medium">View</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 font-medium" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No users found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-6 border-t border-gray-200">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
