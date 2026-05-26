<x-app-layout>
    <div class="mx-auto max-w-4xl space-y-6 p-6">
        <a href="{{ route('jobs.index') }}" class="text-sm font-medium text-green-700 hover:underline">Back to jobs</a>

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-6 flex flex-col gap-3 border-b border-gray-100 pb-6 md:flex-row md:items-start md:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">{{ $job->job_title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $job->employer->name ?? 'Employer' }}</p>
                    <p class="mt-1 text-sm text-gray-500">{{ $job->location }}</p>
                </div>
                <span class="w-fit rounded-full bg-green-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-green-700">
                    {{ $job->job_type }}
                </span>
            </div>

            <div class="grid gap-4 border-b border-gray-100 pb-6 text-sm text-gray-700 md:grid-cols-3">
                <div>
                    <p class="text-xs uppercase text-gray-400">Salary</p>
                    <p class="mt-1 font-medium text-gray-900">
                        @if($job->salary_min || $job->salary_max)
                            PHP {{ number_format($job->salary_min ?? 0) }} - {{ number_format($job->salary_max ?? 0) }}
                        @else
                            Competitive
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-400">Vacancies</p>
                    <p class="mt-1 font-medium text-gray-900">{{ $job->vacancies }}</p>
                </div>
                <div>
                    <p class="text-xs uppercase text-gray-400">Closing Date</p>
                    <p class="mt-1 font-medium text-gray-900">{{ optional($job->closing_date)->format('M d, Y') ?? 'Open until filled' }}</p>
                </div>
            </div>

            <div class="mt-6 space-y-6">
                <section>
                    <h2 class="text-lg font-semibold text-gray-900">Job Description</h2>
                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $job->job_description }}</p>
                </section>

                @if($job->requirements)
                    <section>
                        <h2 class="text-lg font-semibold text-gray-900">Requirements</h2>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $job->requirements }}</p>
                    </section>
                @endif
            </div>
        </div>

        @if(auth()->user()->isSeeker())
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Apply for this job</h2>
                <form method="POST" action="{{ route('applications.store', $job) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label for="cover_letter" class="mb-1 block text-sm font-medium text-gray-700">Cover Letter</label>
                        <textarea id="cover_letter" name="cover_letter" rows="5" class="w-full rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500" placeholder="Write a short message to the employer...">{{ old('cover_letter') }}</textarea>
                        @error('cover_letter')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="rounded-lg bg-green-700 px-5 py-2 text-sm font-medium text-white hover:bg-green-800">
                        Submit Application
                    </button>
                </form>
            </div>
        @endif
    </div>
</x-app-layout>
