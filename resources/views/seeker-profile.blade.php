<x-app-layout>
    @php
        // Prototyping safeguard: If $profile isn't passed from the controller yet, make it an empty object so the page doesn't crash.
        $profile = $profile ?? (object)[]; 
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-8">
        {{-- Profile Header Banner --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden mb-4">
            {{-- Cover --}}
            <div class="h-24 bg-gradient-to-r from-green-600 to-green-800"></div>
            {{-- Info Row --}}
            <div class="px-6 pb-4">
                <div class="flex items-end justify-between -mt-8 mb-3">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center text-lg font-bold text-white ring-4 ring-white shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="flex items-center gap-2 mt-8">
                        <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                           class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-gray-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                            </svg>
                            Account Settings
                        </a>
                        <a href="#"
                           class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-green-700 hover:bg-green-800 text-white rounded-lg transition-colors">
                            + Open to work
                        </a>
                    </div>
                </div>
                <div>
                    <h1 class="text-base font-semibold text-gray-900">{{ auth()->user()->name ?? 'Juan dela Cruz' }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $profile->headline ?? 'BS Information Technology · Xavier University' }}</p>
                    <div class="flex items-center gap-3 mt-1.5 text-xs text-gray-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>
                            </svg>
                            {{ $profile->location ?? 'Cagayan de Oro, Misamis Oriental' }}
                        </span>
                        <span>·</span>
                        <span class="text-green-600">{{ auth()->user()->email ?? 'juan@email.com' }}</span>
                        <span>·</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                            {{ $profile->phone ?? '09XX XXX XXXX' }}
                        </span>
                    </div>
                    {{-- Stats row --}}
                    <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-100">
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-900">8</div>
                            <div class="text-xs text-gray-400">Applied</div>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-amber-600">3</div>
                            <div class="text-xs text-gray-400">Under review</div>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-green-600">1</div>
                            <div class="text-xs text-gray-400">Interviews</div>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="text-center">
                            <div class="text-sm font-semibold text-green-600">24</div>
                            <div class="text-xs text-gray-400">Profile views</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">

            {{-- LEFT COLUMN --}}
            <div class="col-span-1 space-y-3">

                {{-- About --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                    <h2 class="text-sm font-semibold text-gray-900 mb-2">About</h2>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $profile->about ?? 'No bio added yet. Add a short summary about yourself to help employers learn more about you.' }}</p>
                </div>

                {{-- Resume --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-2">
                        <h2 class="text-sm font-semibold text-gray-900">Resume</h2>
                        <a href="#" class="text-xs text-green-600 hover:underline">Upload</a>
                    </div>
                    @if(isset($profile->resume_path))
                        <a href="{{ Storage::url($profile->resume_path) }}" target="_blank"
                           class="flex items-center gap-2 p-2.5 bg-green-50 hover:bg-green-100 border border-green-100 rounded-lg transition-colors group">
                            <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <span class="text-xs text-green-700 font-medium group-hover:underline truncate">View resume</span>
                        </a>
                    @else
                        <div class="flex flex-col items-center justify-center py-4 border border-dashed border-gray-200 rounded-lg text-center">
                            <svg class="w-5 h-5 text-gray-300 mb-1.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                            </svg>
                            <p class="text-xs text-gray-400">No resume yet</p>
                        </div>
                    @endif
                </div>

                {{-- Skills --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-gray-900">Skills</h2>
                        <a href="#" class="text-xs text-green-600 hover:underline">Edit</a>
                    </div>
                    <div class="flex flex-wrap gap-1.5">
                        @forelse($profile->skills ?? [] as $skill)
                            <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded-md border border-green-100 hover:bg-green-100 transition-colors cursor-default">{{ $skill }}</span>
                        @empty
                            @foreach(['PHP','Laravel','JavaScript','HTML/CSS','MySQL','Git','Communication','Problem Solving'] as $s)
                                <span class="px-2 py-0.5 bg-green-50 text-green-700 text-xs rounded-md border border-green-100 hover:bg-green-100 transition-colors cursor-default">{{ $s }}</span>
                            @endforeach
                        @endforelse
                    </div>
                </div>

            </div>

            {{-- RIGHT COLUMN --}}
            <div class="col-span-2 space-y-3">

                {{-- Education --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-gray-900">Education</h2>
                        <a href="#" class="text-xs text-green-600 hover:underline">Edit</a>
                    </div>
                    @forelse($profile->education ?? [] as $edu)
                    <div class="flex items-start gap-3 py-2 hover:bg-gray-50 rounded-lg px-2 transition-colors">
                        <div class="w-9 h-9 bg-green-50 border border-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-gray-900">{{ $edu->degree }}</div>
                            <div class="text-xs text-gray-500">{{ $edu->school }}</div>
                            <div class="text-xs text-gray-400">{{ $edu->year_from }} – {{ $edu->year_to }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="flex items-start gap-3 py-2 px-2 hover:bg-gray-50 rounded-lg transition-colors">
                        <div class="w-9 h-9 bg-green-50 border border-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">BS Information Technology</div>
                            <div class="text-xs text-gray-500">Xavier University — Ateneo de Cagayan</div>
                            <div class="text-xs text-gray-400">2022 – 2026</div>
                        </div>
                    </div>
                    @endforelse
                </div>

                {{-- Work Experience --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-gray-900">Work experience</h2>
                        <a href="#" class="text-xs text-green-600 hover:underline">+ Add</a>
                    </div>
                    @if(isset($profile->experiences) && count($profile->experiences))
                        @foreach($profile->experiences as $exp)
                        <div class="flex items-start gap-3 py-2 px-2 hover:bg-gray-50 rounded-lg transition-colors">
                            <div class="w-9 h-9 bg-gray-100 border border-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900">{{ $exp->position }}</div>
                                <div class="text-xs text-gray-500">{{ $exp->company }}</div>
                                <div class="text-xs text-gray-400">{{ $exp->year_from }} – {{ $exp->year_to ?? 'Present' }}</div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="flex flex-col items-center justify-center py-6 text-center border border-dashed border-gray-200 rounded-lg">
                            <svg class="w-5 h-5 text-gray-300 mb-1.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                            </svg>
                            <p class="text-xs text-gray-400 mb-1">No work experience added yet</p>
                            <a href="#" class="text-xs text-green-600 hover:underline">Add experience</a>
                        </div>
                    @endif
                </div>

                {{-- Recent Applications --}}
                <div class="bg-white border border-gray-200 rounded-xl p-4 hover:shadow-sm transition-shadow duration-200">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-sm font-semibold text-gray-900">Recent applications</h2>
                        <a href="#" class="text-xs text-green-600 hover:underline">View all</a>
                    </div>
                    <div class="space-y-1">
                        @foreach([
                            ['title'=>'Junior Web Developer','company'=>'Growthlab Solutions','status'=>'Under review','color'=>'bg-amber-50 text-amber-700'],
                            ['title'=>'Customer Support Associate','company'=>'Teletech Inc.','status'=>'Interview','color'=>'bg-green-50 text-green-700'],
                            ['title'=>'Data Encoder','company'=>'City Government of CDO','status'=>'Pending','color'=>'bg-green-50 text-green-700'],
                        ] as $app)
                        <div class="flex items-center gap-3 py-2 px-2 hover:bg-gray-50 rounded-lg transition-colors group">
                            <div class="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-3.5 h-3.5 text-gray-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-medium text-gray-900">{{ $app['title'] }}</div>
                                <div class="text-xs text-gray-400">{{ $app['company'] }}</div>
                            </div>
                            <span class="px-2 py-0.5 text-xs font-medium rounded-md {{ $app['color'] }} flex-shrink-0">{{ $app['status'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>