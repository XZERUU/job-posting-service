<x-app-layout>
    <div class="p-6 max-w-7xl mx-auto space-y-6">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Find Jobs</h1>
                <p class="text-sm text-gray-500 mt-1">Browse and apply to the latest opportunities.</p>
            </div>
        </div>

        {{-- Search & Filter Bar --}}
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <form action="{{ route('jobs.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ $search ?? request('search') }}" 
                           placeholder="Search by title, company, location, skill, or job type..." 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <button type="submit" class="px-6 py-2 bg-green-700 text-white rounded-lg hover:bg-green-800 transition">
                    Search
                </button>
            </form>
        </div>

        @if(($recommendedJobs ?? collect())->isNotEmpty())
            <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-900">Suggested Jobs</h2>
                        <p class="text-xs text-gray-500">Based on your profile headline and recent active postings.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($recommendedJobs as $recommended)
                        <a href="{{ route('jobs.show', $recommended) }}" class="block rounded-lg border border-gray-100 p-3 hover:border-green-300 hover:bg-green-50 transition">
                            <div class="text-sm font-medium text-gray-900">{{ $recommended->job_title }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $recommended->employer->name ?? 'Employer' }} · {{ $recommended->location }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Job Listings Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($jobs as $job)
                <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-green-300 hover:shadow-md transition-all group">
                    <div class="flex justify-between items-start mb-3">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-700 transition">{{ $job->job_title }}</h3>
                        <span class="text-[10px] font-bold bg-green-50 text-green-700 px-2 py-1 rounded-full uppercase tracking-wider">
                            {{ $job->job_type ?? 'Full-time' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-1">{{ $job->employer->name ?? 'Employer' }}</p>
                    <p class="text-xs text-gray-400 mb-4">{{ $job->location }}</p>
                    
                    <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-900">
                            @if($job->salary_min || $job->salary_max)
                                PHP {{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }}
                            @else
                                Competitive
                            @endif
                        </span>
                        <a href="{{ route('jobs.show', $job->id) }}" 
                           class="text-sm text-green-700 font-medium hover:underline">View Details</a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-gray-500">
                    No jobs found matching your search.
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $jobs->links() }}
        </div>
    </div>
</x-app-layout>
