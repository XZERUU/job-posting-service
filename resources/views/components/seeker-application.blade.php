<x-layouts.seeker>
    <x-slot name="title">My Applications</x-slot>

    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-lg font-medium text-gray-900">My applications</h1>
        </div>

        {{-- Status Tabs --}}
        <div class="flex gap-1 border-b border-gray-200 mb-4">
            @foreach(['all' => 'All', 'pending' => 'Pending', 'under_review' => 'Under review', 'interview' => 'Interview', 'hired' => 'Hired', 'rejected' => 'Rejected'] as $key => $label)
            <a href="{{ route('seeker.applications', ['status' => $key == 'all' ? null : $key]) }}"
               class="px-3 py-2 text-xs font-medium border-b-2 -mb-px transition-colors
                      {{ (request('status', 'all') == $key) ? 'border-blue-700 text-blue-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
                @if($key == 'all')
                    ({{ isset($applications) ? $applications->total() : 4 }})
                @endif
            </a>
            @endforeach
        </div>

        {{-- Application List --}}
        <div class="bg-white border border-gray-200 rounded-xl divide-y divide-gray-100">
            @forelse($applications ?? [] as $app)
            @php
                $statusColors = [
                    'pending'     => 'bg-blue-50 text-blue-700',
                    'under_review'=> 'bg-amber-50 text-amber-700',
                    'interview'   => 'bg-green-50 text-green-700',
                    'hired'       => 'bg-green-100 text-green-800',
                    'rejected'    => 'bg-red-50 text-red-700',
                ];
                $color = $statusColors[$app->status] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $app->job->title }}</div>
                    <div class="text-xs text-gray-400">{{ $app->job->employer->company_name }} · Applied {{ $app->created_at->format('M d, Y') }}</div>
                </div>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-md {{ $color }}">
                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                </span>
            </div>
            @empty
            @foreach([
                ['title'=>'Junior Web Developer','company'=>'Growthlab Solutions','date'=>'May 20, 2026','status'=>'under_review','color'=>'bg-amber-50 text-amber-700','label'=>'Under review'],
                ['title'=>'Customer Support Associate','company'=>'Teletech Inc.','date'=>'May 18, 2026','status'=>'interview','color'=>'bg-green-50 text-green-700','label'=>'Interview'],
                ['title'=>'Data Encoder','company'=>'City Government of CDO','date'=>'May 15, 2026','status'=>'pending','color'=>'bg-blue-50 text-blue-700','label'=>'Pending'],
                ['title'=>'HR Assistant','company'=>'SM Prime Holdings','date'=>'May 10, 2026','status'=>'rejected','color'=>'bg-red-50 text-red-700','label'=>'Rejected'],
            ] as $p)
            <div class="flex items-center gap-3 px-4 py-3">
                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900">{{ $p['title'] }}</div>
                    <div class="text-xs text-gray-400">{{ $p['company'] }} · Applied {{ $p['date'] }}</div>
                </div>
                <span class="px-2.5 py-0.5 text-xs font-medium rounded-md {{ $p['color'] }}">{{ $p['label'] }}</span>
            </div>
            @endforeach
            @endforelse
        </div>

        @if(isset($applications) && $applications->hasPages())
        <div class="mt-4">{{ $applications->withQueryString()->links() }}</div>
        @endif
    </div>
</x-layouts.seeker>