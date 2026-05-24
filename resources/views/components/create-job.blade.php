<x-layouts.employer>
    <x-slot name="title">Post a Job</x-slot>

    <div class="p-6">

        <div class="mb-6">
            <h1 class="text-lg font-medium text-gray-900">Post a job</h1>
            <p class="text-sm text-gray-400 mt-0.5">Fill in the details for your job opening.</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('employer.jobs.store') }}">
            @csrf

            <div class="grid grid-cols-3 gap-4">

                {{-- Left: Job Details --}}
                <div class="col-span-2 space-y-4">
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <div class="text-xs font-medium text-gray-500 mb-4">Job details</div>

                        <div class="mb-4">
                            <label for="title" class="block text-xs text-gray-500 mb-1">Job title <span class="text-red-500">*</span></label>
                            <input id="title" type="text" name="title" value="{{ old('title') }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                   placeholder="e.g. Junior Web Developer">
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label for="category" class="block text-xs text-gray-500 mb-1">Category <span class="text-red-500">*</span></label>
                                <select id="category" name="category" required
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Select category</option>
                                    @foreach(['IT / Technology','Admin / Office','Healthcare','Customer Service','Education','Engineering','Sales / Marketing','Government'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="employment_type" class="block text-xs text-gray-500 mb-1">Employment type <span class="text-red-500">*</span></label>
                                <select id="employment_type" name="employment_type" required
                                        class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                    <option value="">Select type</option>
                                    @foreach(['Full-time','Part-time','Contract','Internship'] as $type)
                                        <option value="{{ $type }}" {{ old('employment_type') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-xs text-gray-500 mb-1">Job description <span class="text-red-500">*</span></label>
                            <textarea id="description" name="description" rows="5" required
                                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"
                                      placeholder="Describe the role, responsibilities, and what a typical day looks like...">{{ old('description') }}</textarea>
                        </div>

                        <div>
                            <label for="requirements" class="block text-xs text-gray-500 mb-1">Requirements</label>
                            <textarea id="requirements" name="requirements" rows="4"
                                      class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition resize-none"
                                      placeholder="List qualifications, experience, and skills required...">{{ old('requirements') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Right: Settings --}}
                <div class="space-y-4">

                    {{-- Compensation & Location --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <div class="text-xs font-medium text-gray-500 mb-4">Compensation & location</div>

                        <div class="mb-4">
                            <label class="block text-xs text-gray-500 mb-1">Salary range (PHP)</label>
                            <div class="flex items-center gap-2">
                                <input type="number" name="salary_min" value="{{ old('salary_min') }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                       placeholder="Min">
                                <span class="text-xs text-gray-400 flex-shrink-0">to</span>
                                <input type="number" name="salary_max" value="{{ old('salary_max') }}"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                       placeholder="Max">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="location" class="block text-xs text-gray-500 mb-1">Work location <span class="text-red-500">*</span></label>
                            <input id="location" type="text" name="location" value="{{ old('location', 'Cagayan de Oro, MisOr') }}" required
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <div>
                            <label for="work_setup" class="block text-xs text-gray-500 mb-1">Work setup</label>
                            <select id="work_setup" name="work_setup"
                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                @foreach(['On-site','Remote','Hybrid'] as $setup)
                                    <option value="{{ $setup }}" {{ old('work_setup', 'On-site') == $setup ? 'selected' : '' }}>{{ $setup }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Posting Settings --}}
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <div class="text-xs font-medium text-gray-500 mb-4">Posting settings</div>

                        <div class="mb-4">
                            <label for="deadline" class="block text-xs text-gray-500 mb-1">Application deadline</label>
                            <input id="deadline" type="date" name="deadline" value="{{ old('deadline') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <div>
                            <label for="slots" class="block text-xs text-gray-500 mb-1">Slots available</label>
                            <input id="slots" type="number" name="slots" value="{{ old('slots', 1) }}" min="1"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                                   placeholder="e.g. 3">
                        </div>
                    </div>

                    {{-- Actions --}}
                    <button type="submit" name="action" value="publish"
                            class="w-full flex items-center justify-center gap-2 py-2.5 bg-blue-700 hover:bg-blue-800 text-white text-sm font-medium rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Publish job post
                    </button>
                    <button type="submit" name="action" value="draft"
                            class="w-full flex items-center justify-center gap-2 py-2 mt-2 border border-gray-200 text-gray-600 text-sm rounded-lg hover:bg-gray-50 transition-colors">
                        Save as draft
                    </button>
                </div>

            </div>
        </form>
    </div>
</x-layouts.employer>