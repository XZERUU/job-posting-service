<x-app-layout>
<div class="max-w-4xl mx-auto px-4 py-6">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-base font-semibold text-gray-900">Account Settings</h1>
        <p class="text-xs text-gray-400 mt-0.5">Manage your account information, security, and preferences.</p>
    </div>

    {{-- Tab Nav --}}
    <div class="flex gap-0 border-b border-gray-200 mb-6">
        @foreach(['Profile' => '#profile', 'Security' => '#security', 'Danger zone' => '#danger'] as $tab => $href)
        <a href="{{ $href }}"
           class="px-4 py-2 text-xs font-medium border-b-2 -mb-px transition-colors
                  {{ $tab === 'Profile' ? 'border-green-700 text-green-700' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
            {{ $tab }}
        </a>
        @endforeach
    </div>

    <div class="grid grid-cols-3 gap-4">

        {{-- LEFT: Avatar + Links --}}
        <div class="col-span-1 space-y-3">

            {{-- Avatar Card --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-lg font-bold text-white mb-3 shadow-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name ?? 'Juan dela Cruz' }}</div>
                    <div class="text-xs text-gray-400 mt-0.5">{{ auth()->user()->email ?? 'juan@email.com' }}</div>
                    <button class="mt-3 w-full text-xs text-gray-600 border border-gray-200 rounded-lg py-1.5 hover:bg-gray-50 transition-colors">
                        Change photo
                    </button>
                </div>
            </div>

            {{-- Links Card --}}
            <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                <h3 class="text-xs font-semibold text-gray-700 mb-3">Professional links</h3>
                <form method="POST" action="{{ route('profile.update-links') }}" class="space-y-2.5">
                    @csrf
                    @method('patch')

                    <div>
                        <label class="block text-xs text-gray-400 mb-1">LinkedIn URL</label>
                        <div class="flex items-center gap-1.5 px-2.5 py-1.5 border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-green-500 focus-within:border-transparent transition">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}" placeholder="https://linkedin.com/in/yourname"
                                   class="flex-1 text-xs bg-transparent outline-none text-gray-700 placeholder-gray-300 min-w-0">
                        </div>
                        @error('linkedin_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">Portfolio website</label>
                        <div class="flex items-center gap-1.5 px-2.5 py-1.5 border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-green-500 focus-within:border-transparent transition">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profile->portfolio_url ?? '') }}" placeholder="https://yourportfolio.com"
                                   class="flex-1 text-xs bg-transparent outline-none text-gray-700 placeholder-gray-300 min-w-0">
                        </div>
                        @error('portfolio_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs text-gray-400 mb-1">GitHub</label>
                        <div class="flex items-center gap-1.5 px-2.5 py-1.5 border border-gray-200 rounded-lg focus-within:ring-2 focus-within:ring-green-500 focus-within:border-transparent transition">
                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24" style="width:14px;height:14px;flex-shrink:0">
                                <path d="M12 0C5.374 0 0 5.373 0 12c0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <input type="url" name="github_url" value="{{ old('github_url', $profile->github_url ?? '') }}" placeholder="https://github.com/yourusername"
                                   class="flex-1 text-xs bg-transparent outline-none text-gray-700 placeholder-gray-300 min-w-0">
                        </div>
                        @error('github_url')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full text-xs font-medium text-white bg-green-700 hover:bg-green-800 rounded-lg py-1.5 transition-colors mt-1">
                        Save links
                    </button>
                    @if (session('status') === 'links-updated')
                        <p class="text-xs text-green-600">Links saved.</p>
                    @endif
                </form>
            </div>

        </div>

        {{-- RIGHT: Forms --}}
        <div class="col-span-2 space-y-3">

            {{-- Profile Information --}}
            <div id="profile" class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-900">Account Details information</h2>
                    <span class="text-xs text-gray-400">* required</span>
                </div>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Profile Tab Content --}}
<div id="profile" class="space-y-6">

    {{-- 2. Custom Profile Info (Phone/Resume) --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Additional Information</h2>
        
        <form method="POST" action="{{ route('profile.update-custom') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                {{-- Phone --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                           placeholder="0917 123 4567">
                </div>

                {{-- Resume Upload --}}
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Resume (PDF)</label>
                    <input type="file" name="resume" 
                           class="w-full px-3 py-1.5 text-sm border border-gray-200 rounded-lg bg-gray-50 cursor-pointer">
                    @if(isset($profile->resume_path))
                        <p class="mt-1 text-[10px] text-green-600 underline">Current: {{ basename($profile->resume_path) }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs text-gray-500 mb-1">Professional Headline</label>
                <input type="text" name="headline" value="{{ old('headline', $profile->headline ?? '') }}"
                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500"
                       placeholder="e.g. Aspiring Web Developer">
            </div>

            <button type="submit" class="mt-4 px-4 py-2 bg-green-700 text-white text-xs font-medium rounded-lg hover:bg-green-800 transition-colors">
                Save Profile Details
            </button>
        </form>
    </div>

    {{-- 3. Education --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-900">Education</h2>
            <button class="text-xs text-green-700 font-medium hover:underline">+ Add Education</button>
        </div>
        
        @forelse($profile->education ?? [] as $edu)
            <div class="flex items-center justify-between p-3 border border-gray-100 rounded-lg mb-2">
                <div>
                    <div class="text-sm font-medium text-gray-900">{{ $edu->degree }}</div>
                    <div class="text-xs text-gray-500">{{ $edu->school }}</div>
                </div>
                <button class="text-xs text-red-600 hover:underline">Remove</button>
            </div>
        @empty
            <p class="text-xs text-gray-400 italic">No education added yet.</p>
        @endforelse
    </div>
</div>

            {{-- Security --}}
            <div id="security" class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-sm transition-shadow duration-200">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-6 h-6 bg-green-50 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900">Change password</h2>
                </div>
                @include('profile.partials.update-password-form')
            </div>

            {{-- Danger Zone --}}
            <div id="danger" class="bg-white border border-red-100 rounded-xl p-5 hover:shadow-sm transition-shadow duration-200">
                <div class="flex items-center gap-2 mb-1">
                    <div class="w-6 h-6 bg-red-50 rounded-lg flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-semibold text-red-600">Danger zone</h2>
                </div>
                <p class="text-xs text-gray-400 mb-4 ml-8">Deleting your account is permanent and cannot be undone.</p>
                @include('profile.partials.delete-user-form')
            </div>

            
        </div>
    </div>
</div>
</x-app-layout>
