@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Application Details</h1>
                <p class="text-gray-600">Review the applicant information and submitted message.</p>
            </div>
            <a href="{{ route('employer.applications') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Back to Applications</a>
        </div>

        <div class="bg-white rounded-lg shadow p-6 space-y-6">
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <section>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-gray-900">Applicant</h2>
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('employer.applications.update-status', [$application, 'approved']) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('employer.applications.update-status', [$application, 'rejected']) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">Reject</button>
                        </form>
                    </div>
                </div>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-gray-500">Name</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $application->user->name ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Email</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $application->user->email ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Phone</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $application->user->seekerProfile->phone ?? 'Not provided' }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Headline</dt>
                        <dd class="mt-1 font-medium text-gray-900">{{ $application->user->seekerProfile->headline ?? 'Not provided' }}</dd>
                    </div>
                </dl>
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Professional Links</h2>
                @php($profile = $application->user->seekerProfile)
                @if($profile?->linkedin_url || $profile?->portfolio_url || $profile?->github_url)
                    <div class="flex flex-wrap gap-3 text-sm">
                        @if($profile?->linkedin_url)
                            <a href="{{ $profile->linkedin_url }}" target="_blank" class="text-blue-600 hover:underline">LinkedIn</a>
                        @endif
                        @if($profile?->portfolio_url)
                            <a href="{{ $profile->portfolio_url }}" target="_blank" class="text-blue-600 hover:underline">Portfolio</a>
                        @endif
                        @if($profile?->github_url)
                            <a href="{{ $profile->github_url }}" target="_blank" class="text-blue-600 hover:underline">GitHub</a>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">No professional links provided.</p>
                @endif
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Resume</h2>
                @if($application->user->seekerProfile?->resume_path)
                    <a href="{{ Storage::url($application->user->seekerProfile->resume_path) }}" target="_blank" class="inline-flex rounded-lg bg-green-700 px-4 py-2 text-sm font-medium text-white hover:bg-green-800">
                        View Resume PDF
                    </a>
                @else
                    <p class="text-sm text-gray-500">No resume uploaded.</p>
                @endif
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Job Applied For</h2>
                <p class="text-sm font-medium text-gray-900">{{ $application->jobPost->job_title ?? 'N/A' }}</p>
                <p class="text-sm text-gray-600">{{ $application->jobPost->location ?? 'N/A' }}</p>
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Cover Letter</h2>
                <div class="rounded-lg bg-gray-50 p-4 text-sm leading-6 text-gray-700 whitespace-pre-line">
                    {{ $application->cover_letter ?: 'No cover letter submitted.' }}
                </div>
            </section>

            <section class="border-t border-gray-200 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">Status</h2>
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    @if($application->status === 'approved') bg-green-100 text-green-800
                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800
                    @endif">
                    {{ ucfirst($application->status ?? 'pending') }}
                </span>
            </section>
        </div>
    </div>
</div>
@endsection
