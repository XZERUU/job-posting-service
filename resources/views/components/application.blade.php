<x-layouts.employer>
    <x-slot name="title">Application Tracking</x-slot>

    <div class="p-6">

        {{-- Top bar --}}
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-lg font-medium text-gray-900">Application tracking</h1>

            {{-- Job filter --}}
            <form method="GET" action="{{ route('employer.applications.index') }}">
                <select name="job_id" onchange="this.form.submit()"
                        class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    <option value="">All job posts</option>
                    @foreach($jobs ?? [] as $job)
                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                            {{ $job->title }}
                        </option>
                    @endforeach
                    @if(!isset($jobs) || $jobs->isEmpty())
                        <option selected>Junior Web Developer</option>
                        <option>UI/UX Designer</option>
                        <option>Database Administrator</option>
                    @endif
                </select>
            </form>
        </div>

        {{-- Status Summary --}}
        <div class="grid grid-cols-5 gap-3 mb-6">
            @foreach([
                ['label' => 'Pending',      'key' => 'pending',      'value' => $counts['pending']      ?? 8,  'color' => 'text-blue-700'],
                ['label' => 'Under review', 'key' => 'under_review', 'value' => $counts['under_review'] ?? 6,  'color' => 'text-amber-700'],
                ['label' => 'Interview',    'key' => 'interview',    'value' => $counts['interview']    ?? 3,  'color' => 'text-green-700'],
                ['label' => 'Hired',        'key' => 'hired',        'value' => $counts['hired']        ?? 1,  'color' => 'text-blue-700'],
                ['label' => 'Rejected',     'key' => 'rejected',     'value' => $counts['rejected']     ?? 2,  'color' => 'text-red-600'],
            ] as $stat)
            <div class="bg-gray-100 rounded-xl p-4">
                <div class="text-xs text-gray-500 mb-1">{{ $stat['label'] }}</div>
                <div class="text-2xl font-medium {{ $stat['color'] }}">{{ $stat['value'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Applicant List --}}
        <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">

            {{-- Table header --}}
            <div class="grid grid-cols-12 gap-3 px-4 py-2 text-xs font-medium text-gray-400">
                <div class="col-span-4">Applicant</div>
                <div class="col-span-3">Applied for</div>
                <div class="col-span-2">Date applied</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-1"></div>
            </div>

            @forelse($applications ?? [] as $app)
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
            <div class="grid grid-cols-12 gap-3 items-center px-4 py-3">
                <div class="col-span-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium text-blue-700 flex-shrink-0">
                        {{ strtoupper(substr($app->user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-900 truncate">{{ $app->user->name }}</div>
                        <div class="text-xs text-gray-400 truncate">{{ $app->user->profile->education ?? 'BS Information Technology' }}</div>
                    </div>
                </div>
                <div class="col-span-3 text-sm text-gray-600 truncate">{{ $app->job->title }}</div>
                <div class="col-span-2 text-xs text-gray-400">{{ $app->created_at->format('M d, Y') }}</div>
                <div class="col-span-2">
                    <form method="POST" action="{{ route('employer.applications.update', $app) }}">
                        @csrf @method('PATCH')
                        <select name="status" onchange="this.form.submit()"
                                class="w-full px-2 py-1 text-xs border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 transition">
                            @foreach(['pending'=>'Pending','under_review'=>'Under review','interview'=>'Interview','hired'=>'Hired','rejected'=>'Rejected'] as $val => $label)
                                <option value="{{ $val }}" {{ $app->status == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div class="col-span-1 flex justify-end">
                    <a href="{{ route('employer.applications.show', $app) }}"
                       class="text-xs text-blue-700 hover:underline">View</a>
                </div>
            </div>
            @empty
            {{-- Placeholder rows --}}
            @foreach([
                ['initials'=>'JD','name'=>'Juan dela Cruz','edu'=>'BS Information Technology','job'=>'Junior Web Developer','date'=>'May 20, 2026','status'=>'under_review','label'=>'Under review','color'=>'bg-amber-50 text-amber-700'],
                ['initials'=>'MA','name'=>'Maria Abad','edu'=>'BS Computer Science','job'=>'Junior Web Developer','date'=>'May 19, 2026','status'=>'interview','label'=>'Interview','color'=>'bg-green-50 text-green-700'],
                ['initials'=>'RL','name'=>'Rico Lim','edu'=>'BS Information Systems','job'=>'UI/UX Designer','date'=>'May 18, 2026','status'=>'pending','label'=>'Pending','color'=>'bg-blue-50 text-blue-700'],
                ['initials'=>'AC','name'=>'Ana Castillo','edu'=>'BS IT','job'=>'UI/UX Designer','date'=>'May 17, 2026','status'=>'pending','label'=>'Pending','color'=>'bg-blue-50 text-blue-700'],
                ['initials'=>'BT','name'=>'Ben Torres','edu'=>'BS Computer Engineering','job'=>'Database Administrator','date'=>'May 15, 2026','status'=>'hired','label'=>'Hired','color'=>'bg-green-100 text-green-800'],
            ] as $p)
            <div class="grid grid-cols-12 gap-3 items-center px-4 py-3">
                <div class="col-span-4 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium text-blue-700 flex-shrink-0">
                        {{ $p['initials'] }}
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-medium text-gray-900">{{ $p['name'] }}</div>
                        <div class="text-xs text-gray-400">{{ $p['edu'] }}</div>
                    </div>
                </div>
                <div class="col-span-3 text-sm text-gray-600">{{ $p['job'] }}</div>
                <div class="col-span-2 text-xs text-gray-400">{{ $p['date'] }}</div>
                <div class="col-span-2">
                    <select class="w-full px-2 py-1 text-xs border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @foreach(['pending'=>'Pending','under_review'=>'Under review','interview'=>'Interview','hired'=>'Hired','rejected'=>'Rejected'] as $val => $label)
                            <option value="{{ $val }}" {{ $p['status'] == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-span-1 flex justify-end">
                    <a href="#" class="text-xs text-blue-700 hover:underline">View</a>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>

        @if(isset($applications) && $applications->hasPages())
        <div class="mt-4">{{ $applications->withQueryString()->links() }}</div>
        @endif

    </div>
</x-layouts.employer>