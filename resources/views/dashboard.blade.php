<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Welcome back, {{ auth()->user()->name ?? 'Juan' }}!</h1>
                <p class="text-sm text-gray-500 mt-1">Here is what's happening with your job search today.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ Route::has('jobs.index') ? route('jobs.index') : '#' }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-green-700 border border-transparent rounded-lg hover:bg-green-800 focus:ring-4 focus:ring-green-100 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607"/>
                    </svg>
                    Find Jobs
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-green-50 text-green-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['applied'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 font-medium">Jobs Applied</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['under_review'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 font-medium">Under Review</div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-lime-50 text-lime-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['hired'] ?? 0 }}</div>
                        <div class="text-sm text-gray-500 font-medium">Hired</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                        <h2 class="text-sm font-semibold text-gray-800">Recent Applications</h2>
                        <a href="{{ Route::has('applications.index') ? route('applications.index') : '#' }}" class="text-sm text-green-700 hover:text-green-800 font-medium">View All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-white text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-5 py-3 font-medium">Job Title</th>
                                    <th class="px-5 py-3 font-medium">Company</th>
                                    <th class="px-5 py-3 font-medium">Status</th>
                                    <th class="px-5 py-3 font-medium">Date Applied</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($recentApplications ?? [] as $app)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3 font-medium text-gray-900">{{ $app->job_title }}</td>
                                    <td class="px-5 py-3 text-gray-600">{{ $app->company_name }}</td>
                                    <td class="px-5 py-3">
                                        @if($app->status === 'pending')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Pending</span>
                                        @elseif(in_array($app->status, ['reviewing', 'under_review']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Under Review</span>
                                        @elseif($app->status === 'interview')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-lime-100 text-lime-800">Interview</span>
                                        @elseif(in_array($app->status, ['approved', 'hired']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-emerald-100 text-emerald-800">Hired</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">{{ ucfirst($app->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-gray-500">{{ \Carbon\Carbon::parse($app->created_at)->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-8 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <p class="text-sm text-gray-500">You haven't applied to any jobs yet.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="px-5 py-4 border-b border-gray-200 flex justify-between items-center bg-gray-50/50">
                        <div>
                            <h2 class="text-sm font-semibold text-gray-800">Recommended Jobs</h2>
                            <p class="text-xs text-gray-400 mt-0.5">Fresh active postings you have not applied to yet</p>
                        </div>
                    </div>
                    <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($recommendedJobs ?? [] as $job)
                        <div class="border border-gray-100 rounded-lg p-4 hover:border-green-300 hover:shadow-sm transition-all group">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-green-600 transition-colors">{{ $job->job_title }}</h3>
                            </div>
                            <p class="text-xs text-gray-500 mb-3">{{ $job->company_name }} - {{ $job->location }}</p>
                            <div class="flex gap-2">
                                <a href="{{ Route::has('jobs.show') ? route('jobs.show', $job->id) : '#' }}" class="text-xs font-medium text-green-600 hover:text-green-800">View Details &rarr;</a>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-2 py-6 text-center">
                            <p class="text-sm text-gray-500">No available recommendations yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-4">Profile Strength</h2>
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-3xl font-bold text-gray-900">{{ $profileCompletion ?? 0 }}%</span>
                        <span class="text-xs font-medium text-green-600">
                            @if(($profileCompletion ?? 0) >= 75)
                                Strong
                            @elseif(($profileCompletion ?? 0) >= 40)
                                Intermediate
                            @else
                                Starter
                            @endif
                        </span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-4">
                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $profileCompletion ?? 0 }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 mb-4">A complete profile increases your chances of getting hired.</p>
                    <div class="space-y-2 mb-4">
                        <div class="flex items-center text-sm gap-2 {{ ($completionChecklist['basic_information'] ?? false) ? 'text-gray-400' : 'text-gray-700 font-medium' }}">
                            <svg class="w-4 h-4 {{ ($completionChecklist['basic_information'] ?? false) ? 'text-green-500' : 'text-gray-300' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="{{ ($completionChecklist['basic_information'] ?? false) ? 'line-through' : '' }}">Basic Information</span>
                        </div>
                        <div class="flex items-center text-sm gap-2 {{ ($completionChecklist['headline'] ?? false) ? 'text-gray-400' : 'text-gray-700 font-medium' }}">
                            <svg class="w-4 h-4 {{ ($completionChecklist['headline'] ?? false) ? 'text-green-500' : 'text-gray-300' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="{{ ($completionChecklist['headline'] ?? false) ? 'line-through' : '' }}">Professional Headline</span>
                        </div>
                        <div class="flex items-center text-sm gap-2 {{ ($completionChecklist['phone'] ?? false) ? 'text-gray-400' : 'text-gray-700 font-medium' }}">
                            <svg class="w-4 h-4 {{ ($completionChecklist['phone'] ?? false) ? 'text-green-500' : 'text-gray-300' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="{{ ($completionChecklist['phone'] ?? false) ? 'line-through' : '' }}">Phone Number</span>
                        </div>
                        <div class="flex items-center text-sm gap-2 {{ ($completionChecklist['resume'] ?? false) ? 'text-gray-400' : 'text-gray-700 font-medium' }}">
                            <svg class="w-4 h-4 {{ ($completionChecklist['resume'] ?? false) ? 'text-green-500' : 'text-gray-300' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            <span class="{{ ($completionChecklist['resume'] ?? false) ? 'line-through' : '' }}">Resume</span>
                        </div>
                    </div>
                    <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="block w-full py-2 px-4 border border-gray-200 text-center rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                        Complete Profile
                    </a>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <h2 class="text-sm font-semibold text-gray-800 mb-3">Quick Actions</h2>
                    <div class="space-y-2">
                        <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}" class="flex items-center p-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                            <div class="w-8 h-8 rounded bg-gray-100 group-hover:bg-green-100 flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            </div>
                            Update Resume
                        </a>
                        <a href="{{ Route::has('applications.index') ? route('applications.index') : '#' }}" class="flex items-center p-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-green-50 hover:text-green-700 transition-colors group">
                            <div class="w-8 h-8 rounded bg-gray-100 group-hover:bg-green-100 flex items-center justify-center mr-3 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                            </div>
                            Track Applications
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
