<x-app-layout>
@php
    $profile = $profile ?? (object) [];
    $skillsText = old('skills', is_array($profile->skills ?? null) ? implode(', ', $profile->skills) : '');
    $education = is_array($profile->education ?? null) ? ($profile->education[0] ?? []) : [];
    $experience = is_array($profile->experiences ?? null) ? ($profile->experiences[0] ?? []) : [];
@endphp

<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-900">Account Settings</h1>
        <p class="text-xs text-gray-400 mt-0.5">Manage your account information, profile details, and resume.</p>
    </div>

    <div class="flex gap-0 border-b border-gray-200 mb-6">
        @foreach(['Profile' => '#profile', 'Links' => '#links', 'Security' => '#security', 'Danger zone' => '#danger'] as $tab => $href)
            <a href="{{ $href }}"
               class="px-4 py-2 text-xs font-medium border-b-2 -mb-px transition-colors
               {{ $tab === 'Profile' ? 'border-green-700 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $tab }}
            </a>
        @endforeach
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-xs text-green-700">
            Changes saved.
        </div>
    @endif

    <div class="grid grid-cols-3 gap-4">
        <div class="col-span-1 space-y-3">
            <div class="bg-white border border-gray-200 rounded-xl p-4">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-lg font-bold text-white mb-3 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div id="links" class="bg-white border border-gray-200 rounded-xl p-4">
                <h3 class="text-xs font-semibold text-gray-700 mb-3">Professional links</h3>
                <form method="POST" action="{{ route('profile.update-links') }}" class="space-y-3">
                    @csrf
                    @method('patch')

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">LinkedIn URL</label>
                        <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}"
                               placeholder="https://linkedin.com/in/yourname"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                        @error('linkedin_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Portfolio website</label>
                        <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url ?? '') }}"
                               placeholder="https://yourportfolio.com"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                        @error('portfolio_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">GitHub</label>
                        <input type="url" name="github_url" value="{{ old('github_url', $profile->github_url ?? '') }}"
                               placeholder="https://github.com/yourusername"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                        @error('github_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full text-xs font-medium text-white bg-green-700 hover:bg-green-800 rounded-lg py-2 transition-colors">
                        Save links
                    </button>
                </form>
            </div>
        </div>

        <div class="col-span-2 space-y-3">
            <div id="profile" class="bg-white border border-gray-200 rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-900">Account information</h2>
                    <span class="text-xs text-gray-400">* required</span>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Job seeker profile</h2>

                <form method="POST" action="{{ route('profile.update-custom') }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="0917 123 4567">
                            @error('phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Location</label>
                            <input type="text" name="location" value="{{ old('location', $profile->location ?? '') }}"
                                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="Cagayan de Oro, Misamis Oriental">
                            @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Professional Headline</label>
                        <input type="text" name="headline" value="{{ old('headline', $profile->headline ?? '') }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                               placeholder="e.g. Aspiring Web Developer">
                        @error('headline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">About</label>
                        <textarea name="about" rows="4"
                                  class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                  placeholder="Write a short summary for employers.">{{ old('about', $profile->about ?? '') }}</textarea>
                        @error('about') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Skills</label>
                        <input type="text" name="skills" value="{{ $skillsText }}"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                               placeholder="PHP, Laravel, MySQL, Communication">
                        <p class="mt-1 text-[11px] text-gray-400">Separate skills with commas.</p>
                        @error('skills') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Resume (PDF)</label>
                        <input type="file" name="resume"
                               class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg bg-gray-50 cursor-pointer">
                        @if(isset($profile->resume_path))
                            <a class="mt-1 inline-block text-[11px] text-green-700 underline" href="{{ Storage::url($profile->resume_path) }}" target="_blank">
                                View current resume
                            </a>
                        @endif
                        @error('resume') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div id="education" class="border-t border-gray-100 pt-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Education</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="education_degree" value="{{ old('education_degree', $education['degree'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="Degree or course">
                            <input type="text" name="education_school" value="{{ old('education_school', $education['school'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="School">
                            <input type="text" name="education_year_from" value="{{ old('education_year_from', $education['year_from'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="Start year">
                            <input type="text" name="education_year_to" value="{{ old('education_year_to', $education['year_to'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="End year">
                        </div>
                    </div>

                    <div id="experience" class="border-t border-gray-100 pt-5">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Work experience</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="experience_position" value="{{ old('experience_position', $experience['position'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="Position">
                            <input type="text" name="experience_company" value="{{ old('experience_company', $experience['company'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="Company">
                            <input type="text" name="experience_year_from" value="{{ old('experience_year_from', $experience['year_from'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="Start year">
                            <input type="text" name="experience_year_to" value="{{ old('experience_year_to', $experience['year_to'] ?? '') }}"
                                   class="px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                                   placeholder="End year or Present">
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-green-700 text-white text-xs font-medium rounded-lg hover:bg-green-800 transition-colors">
                        Save Profile Details
                    </button>
                </form>
            </div>

            <div id="security" class="bg-white border border-gray-200 rounded-xl p-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Change password</h2>
                @include('profile.partials.update-password-form')
            </div>

            <div id="danger" class="bg-white border border-red-100 rounded-xl p-5">
                <h2 class="text-sm font-semibold text-red-600 mb-2">Danger zone</h2>
                <p class="text-xs text-gray-400 mb-4">Deleting your account is permanent and cannot be undone.</p>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
</x-app-layout>
