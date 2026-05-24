@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-600">User details and management</p>
            </div>
            <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">← Back to Users</a>
        </div>

        <!-- User Information -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">User Information</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                        <p class="text-lg text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <p class="text-lg text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <p class="text-lg">
                            @if($user->email_verified_at)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">✓ Verified</span>
                            @else
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">⏱ Unverified</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Joined</label>
                        <p class="text-lg text-gray-900">{{ $user->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Role -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Change User Role</h2>
            </div>
            <form action="{{ route('admin.users.update-role', $user) }}" method="POST" class="p-6">
                @csrf
                @method('PATCH')
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">User Role</label>
                    <select name="role" id="role" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="seeker" @selected($user->role === 'seeker')>Seeker (Job Applicant)</option>
                        <option value="employer" @selected($user->role === 'employer')>Employer (Can post jobs)</option>
                        <option value="admin" @selected($user->role === 'admin')>Admin</option>
                    </select>
                    @error('role')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Role</button>
            </form>
        </div>

        <!-- Danger Zone -->
        <div class="bg-red-50 rounded-lg shadow overflow-hidden border border-red-200">
            <div class="p-6 border-b border-red-200">
                <h2 class="text-xl font-semibold text-red-900">Danger Zone</h2>
            </div>
            <div class="p-6">
                <p class="text-red-700 mb-4">Deleting this user will permanently remove all their data from the system.</p>
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="return confirm('Are you absolutely sure? This cannot be undone!')">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
