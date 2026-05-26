<x-app-layout>
    <div class="p-6 max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('applications.index') }}" class="text-sm font-medium text-green-700 hover:underline">Back to applications</a>
            <h1 class="text-2xl font-semibold text-gray-900 mt-3">Edit Application</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $application->jobPost->job_title ?? 'Job' }} · {{ $application->jobPost->employer->name ?? 'Employer' }}</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <form method="POST" action="{{ route('applications.update', $application) }}" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label for="cover_letter" class="block text-sm font-medium text-gray-700 mb-1">Cover Letter</label>
                    <textarea id="cover_letter" name="cover_letter" rows="8" class="w-full rounded-lg border-gray-300 text-sm focus:border-green-500 focus:ring-green-500">{{ old('cover_letter', $application->cover_letter) }}</textarea>
                    @error('cover_letter')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-green-700 px-5 py-2 text-sm font-medium text-white hover:bg-green-800">
                        Save Changes
                    </button>
                    <a href="{{ route('applications.index') }}" class="rounded-lg border border-gray-300 px-5 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
