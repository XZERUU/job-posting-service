<x-app-layout>
@php
    $profile = $profile ?? (object) [];
    $educationItems = is_array($profile->education ?? null) ? $profile->education : [];
    $experienceItems = is_array($profile->experiences ?? null) ? $profile->experiences : [];
    $skillItems = is_array($profile->skills ?? null) ? $profile->skills : [];

    $statusClasses = [
        'pending' => 'bg-amber-50 text-amber-700',
        'approved' => 'bg-green-50 text-green-700',
        'rejected' => 'bg-red-50 text-red-700',
    ];
@endphp

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4">
        <div class="h-24 bg-gradient-to-r from-green-600 to-green-800"></div>
        <div class="px-6 pb-4">
            <div class="flex items-end justify-between -mt-8 mb-3">
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-lg font-bold text-white ring-4 ring-white shadow-sm flex-shrink-0">
                    {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                </div>
                <div class="flex items-center gap-2 mt-8">
                    <a href="{{ route('profile.edit') }}"
                       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-gray-700">
                        Account Settings
                    </a>
                    <span class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-green-700 text-white rounded-lg">
                        + Open to work
                    </span>
                </div>
            </div>

            <h1 class="text-base font-semibold text-gray-900">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ $profile->headline ?? 'No headline added yet' }}</p>
            <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-gray-400">
                <span>{{ $profile->location ?? 'No location added yet' }}</span>
                <span>-</span>
                <span class="text-green-600">{{ $user->email }}</span>
                <span>-</span>
                <span>{{ $profile->phone ?? 'No phone added yet' }}</span>
            </div>

            <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100">
                <div class="text-center">
                    <div class="text-sm font-semibold text-gray-900">{{ $stats['applied'] ?? 0 }}</div>
                    <div class="text-xs text-gray-400">Applied</div>
                </div>
                <div class="w-px h-6 bg-gray-200"></div>
                <div class="text-center">
                    <div class="text-sm font-semibold text-amber-600">{{ $stats['under_review'] ?? 0 }}</div>
                    <div class="text-xs text-gray-400">Under review</div>
                </div>
                <div class="w-px h-6 bg-gray-200"></div>
                <div class="text-center">
                    <div class="text-sm font-semibold text-green-600">{{ $stats['approved'] ?? 0 }}</div>
                    <div class="text-xs text-gray-400">Approved</div>
                </div>
                <div class="w-px h-6 bg-gray-200"></div>
                <div class="text-center">
                    <div class="text-sm font-semibold text-green-600">{{ $stats['profile_views'] ?? 0 }}</div>
                    <div class="text-xs text-gray-400">Profile views</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-1 space-y-3">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <h2 class="text-sm font-semibold text-gray-900 mb-2">About</h2>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $profile->about ?? 'No bio added yet. Add a short summary about yourself to help employers learn more about you.' }}</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-sm font-semibold text-gray-900">Resume</h2>
                    <a href="{{ route('profile.edit') }}" class="text-xs text-green-600 hover:underline">Upload</a>
                </div>
                @if(isset($profile->resume_path))
                    <a href="{{ Storage::url($profile->resume_path) }}" target="_blank"
                       class="block p-2.5 bg-green-50 hover:bg-green-100 border border-green-100 rounded-lg text-xs text-green-700 font-medium">
                        View resume
                    </a>
                @else
                    <p class="text-xs text-gray-400">No resume uploaded yet.</p>
                @endif
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-900">Skills</h2>
                    <a href="{{ route('profile.edit') }}#profile" class="text-xs text-green-600 hover:underline">Edit</a>
                </div>
                <div class="flex flex-wrap gap-1.5">
                    @forelse($skillItems as $skill)
                        <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded-md border border-green-100">{{ $skill }}</span>
                    @empty
                        <span class="text-xs text-gray-400">No skills added yet.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-span-2 space-y-3">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-900">Education</h2>
                    <a href="{{ route('profile.edit') }}#education" class="text-xs text-green-600 hover:underline">Edit</a>
                </div>
                @forelse($educationItems as $edu)
                    <div class="py-2">
                        <div class="text-sm font-medium text-gray-900">{{ $edu['degree'] ?? 'Untitled education' }}</div>
                        <div class="text-xs text-gray-500">{{ $edu['school'] ?? 'School not provided' }}</div>
                        <div class="text-xs text-gray-400">{{ $edu['year_from'] ?? '' }} @if(!empty($edu['year_to'])) - {{ $edu['year_to'] }} @endif</div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No education added yet.</p>
                @endforelse
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-900">Work experience</h2>
                    <a href="{{ route('profile.edit') }}#experience" class="text-xs text-green-600 hover:underline">+ Add</a>
                </div>
                @forelse($experienceItems as $exp)
                    <div class="py-2">
                        <div class="text-sm font-medium text-gray-900">{{ $exp['position'] ?? 'Untitled position' }}</div>
                        <div class="text-xs text-gray-500">{{ $exp['company'] ?? 'Company not provided' }}</div>
                        <div class="text-xs text-gray-400">{{ $exp['year_from'] ?? '' }} @if(!empty($exp['year_to'])) - {{ $exp['year_to'] }} @endif</div>
                    </div>
                @empty
                    <p class="text-xs text-gray-400">No work experience added yet.</p>
                    <a href="{{ route('profile.edit') }}#experience" class="text-xs text-green-600 hover:underline">Add experience</a>
                @endforelse
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-semibold text-gray-900">Recent applications</h2>
                    <a href="{{ route('applications.index') }}" class="text-xs text-green-600 hover:underline">View all</a>
                </div>
                <div class="space-y-1">
                    @forelse($recentApplications as $application)
                        @php
                            $status = strtolower($application->status ?? 'pending');
                            $class = $statusClasses[$status] ?? 'bg-gray-50 text-gray-700';
                        @endphp
                        <div class="flex items-center gap-3 py-2 px-2 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-medium text-gray-900">{{ $application->jobPost->job_title ?? 'Deleted job post' }}</div>
                                <div class="text-xs text-gray-400">{{ $application->jobPost->employer->name ?? 'Unknown employer' }}</div>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-md {{ $class }}">{{ ucfirst($status) }}</span>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">No applications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
