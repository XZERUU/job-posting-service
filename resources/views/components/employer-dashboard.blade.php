<x-layouts.employer>
    <x-slot name="title">Employer Dashboard</x-slot>

    <div class="p-6">

        {{-- Top bar --}}
        <div class="flex items-start justify-between mb-6">
            <div>
                <h1 class="text-lg font-medium text-gray-900">Employer dashboard</h1>
                <p class="text-sm text-gray-400 mt-0.5">{{ auth()->user()->employer->company_name ?? 'Your Company' }}</p>
            </div>
            <a href="{{ route('employer.jobs.create') }}"
               class="flex items-center gap-1.5 px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Post a job
            </a>
        </div>

        {{-- Metrics --}}
        <div class="grid grid-cols-4 gap-3 mb-6">
            <div class="bg-gray-100 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Active postings</div>
                <div class="text-2xl font-medium text-gray-900">{{ $activeJobs ?? 0 }}</div>
            </div>
            <div class="bg-gray-100 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Total applicants</div>
                <div class="text-2xl font-medium text-gray-900">{{ $totalApplicants ?? 0 }}</div>
            </div>
            <div class="bg-gray-100 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Under review</div>
                <div class="text-2xl font-medium text-gray-900">{{ $underReview ?? 0 }}</div>
            </div>
            <div class="bg-gray-100 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">Hired</div>
                <div class="text-2xl font-medium text-gray-900">{{ $hired ?? 0 }}</div>
            </div>
        </div>

        {{-- Recent Applicants --}}
        <div class="mb-2 flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-500">Recent applicants</h2>
            <a href="{{ route('employer.applications.index') }}" class="text-xs text-blue-700 hover:underline">View all →</a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100 mb-6">
            @forelse($recentApplicants ?? [] as $app)
            @php
                $statusColors = [
                    'pending'      => 'bg-blue-50 text-blue-700',
                    'under_review' => 'bg-amber-50 text-amber-700',
                    'interview'    => 'bg-green-50 text-green-700',
                    'hired'        => 'bg-green-100 text-green-800',
                    'rejected'     => 'bg-red-50 text-red-700',
                ];
                $color = $statusColors[$app->status] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium text-blue-700 flex-shrink-0">
                    {{ strtoupper(substr($app->user->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $app->user->name }}</div>
                    <div class="text-xs text-gray-400">Applied for {{ $app->job->title }}</div>
                </div>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-md {{ $color }}">
                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                </span>
            </div>
            @empty
            @foreach([
                ['initials'=>'JD','name'=>'Juan dela Cruz','job'=>'Junior Web Developer','status'=>'Under review','color'=>'bg-amber-50 text-amber-700'],
                ['initials'=>'MA','name'=>'Maria Abad','job'=>'UI/UX Designer','status'=>'Interview','color'=>'bg-green-50 text-green-700'],
                ['initials'=>'RL','name'=>'Rico Lim','job'=>'Junior Web Developer','status'=>'Pending','color'=>'bg-blue-50 text-blue-700'],
                ['initials'=>'AC','name'=>'Ana Castillo','job'=>'UI/UX Designer','status'=>'Pending','color'=>'bg-blue-50 text-blue-700'],
            ] as $p)
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium text-blue-700 flex-shrink-0">
                    {{ $p['initials'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $p['name'] }}</div>
                    <div class="text-xs text-gray-400">Applied for {{ $p['job'] }}</div>
                </div>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-md {{ $p['color'] }}">{{ $p['status'] }}</span>
            </div>
            @endforeach
            @endforelse
        </div>

        {{-- My Job Posts --}}
        <div class="mb-2 flex items-center justify-between">
            <h2 class="text-sm font-medium text-gray-500">My job posts</h2>
            <a href="{{ route('employer.jobs.index') }}" class="text-xs text-blue-700 hover:underline">View all →</a>
        </div>

        <div class="space-y-2">
            @forelse($recentJobs ?? [] as $job)
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $job->title }}</div>
                    <div class="text-xs text-gray-400">Posted {{ $job->created_at->format('M d, Y') }} · {{ $job->applications_count }} applicants</div>
                </div>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-md {{ $job->is_active ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $job->is_active ? 'Active' : 'Closed' }}
                </span>
                <a href="{{ route('employer.applications.index', ['job' => $job->id]) }}"
                   class="text-xs text-blue-700 border border-blue-200 px-2.5 py-1 rounded-lg hover:bg-blue-50 transition-colors">
                    View
                </a>
            </div>
            @empty
            @foreach([
                ['title'=>'Junior Web Developer','date'=>'May 21, 2026','count'=>18,'active'=>true],
                ['title'=>'UI/UX Designer','date'=>'May 18, 2026','count'=>12,'active'=>true],
                ['title'=>'Database Administrator','date'=>'May 10, 2026','count'=>8,'active'=>false],
            ] as $p)
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $p['title'] }}</div>
                    <div class="text-xs text-gray-400">Posted {{ $p['date'] }} · {{ $p['count'] }} applicants</div>
                </div>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-md {{ $p['active'] ? 'bg-green-50 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $p['active'] ? 'Active' : 'Closed' }}
                </span>
                <a href="{{ route('employer.applications.index') }}"
                   class="text-xs text-blue-700 border border-blue-200 px-2.5 py-1 rounded-lg hover:bg-blue-50 transition-colors">
                    View
                </a>
            </div>
            @endforeach
            @endforelse
        </div>

    </div>
</x-layouts.employer>