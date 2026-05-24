<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PESO-Link MisOr') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
            @layer theme{:root{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif;}}
        </style>
    @endif
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-700 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </div>
                <span class="text-sm font-medium text-gray-900">PESO-Link MisOr</span>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="px-4 py-1.5 text-sm font-medium text-green-700 border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                        Go to dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-1.5 text-sm text-gray-600 hover:text-gray-900 transition-colors">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-4 py-1.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 transition-colors">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <section class="max-w-5xl mx-auto px-4 pt-16 pb-12 text-center">
        <span class="inline-block px-3 py-1 text-xs font-medium text-green-700 bg-green-50 border border-green-100 rounded-full mb-4">
            PESO Office — Misamis Oriental
        </span>
        <h1 class="text-4xl font-medium text-gray-900 leading-tight mb-4">
            Find your next job<br class="hidden sm:block"> in Misamis Oriental
        </h1>
        <p class="text-base text-gray-500 max-w-lg mx-auto mb-8">
            PESO-Link MisOr connects job seekers with employers across Cagayan de Oro and the rest of Misamis Oriental — fast, free, and official.
        </p>

        {{-- Search Bar --}}
        <div class="flex flex-col sm:flex-row gap-2 max-w-xl mx-auto mb-4">
            <input type="text"
                   placeholder="Job title, keyword..."
                   class="flex-1 px-4 py-2.5 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 transition">
            <a href="{{ route('register') }}"
               class="px-5 py-2.5 text-sm font-medium text-white bg-green-700 rounded-lg hover:bg-green-800 transition-colors whitespace-nowrap">
                Search jobs
            </a>
        </div>
        <p class="text-xs text-gray-400">Browse 200+ active job listings — no account needed to search</p>
    </section>

    {{-- Stats --}}
    <section class="max-w-5xl mx-auto px-4 pb-14">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl font-medium text-green-700 mb-1">200+</div>
                <div class="text-xs text-gray-500">Active job listings</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl font-medium text-green-700 mb-1">80+</div>
                <div class="text-xs text-gray-500">Registered employers</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl font-medium text-green-700 mb-1">1,200+</div>
                <div class="text-xs text-gray-500">Job seekers registered</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 text-center">
                <div class="text-2xl font-medium text-green-700 mb-1">350+</div>
                <div class="text-xs text-gray-500">Placements this year</div>
            </div>
        </div>
    </section>

    {{-- Featured Job Categories --}}
    <section class="max-w-5xl mx-auto px-4 pb-14">
        <h2 class="text-base font-medium text-gray-700 mb-4">Browse by category</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['icon' => 'M9 3H5a2 2 0 00-2 2v4m6-6h10a2 2 0 012 2v4M9 3v18m0 0h10a2 2 0 002-2V9M9 21H5a2 2 0 01-2-2V9m0 0h18', 'label' => 'IT / Technology', 'count' => 48],
                ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-2 10v-5a1 1 0 00-1-1h-2a1 1 0 00-1 1v5m4 0H9', 'label' => 'Admin / Office', 'count' => 35],
                ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Healthcare', 'count' => 29],
                ['icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'label' => 'Customer service', 'count' => 22],
                ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Education', 'count' => 18],
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'label' => 'Engineering', 'count' => 15],
                ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Sales / Marketing', 'count' => 20],
                ['icon' => 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'label' => 'Government', 'count' => 14],
            ] as $cat)
            <a href="{{ route('register') }}"
               class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 hover:border-green-300 hover:bg-green-50 transition-colors group">
                <div class="w-8 h-8 bg-green-50 group-hover:bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0 transition-colors">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $cat['icon'] }}"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-medium text-gray-800">{{ $cat['label'] }}</div>
                    <div class="text-xs text-gray-400">{{ $cat['count'] }} jobs</div>
                </div>
            </a>
            @endforeach
        </div>
    </section>

    {{-- Recent Job Listings --}}
    <section class="max-w-5xl mx-auto px-4 pb-14">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-medium text-gray-700">Recent job listings</h2>
            <a href="{{ route('register') }}" class="text-sm text-green-700 hover:underline">View all →</a>
        </div>
        <div class="space-y-2">
            @foreach([
                ['title' => 'Junior Web Developer', 'company' => 'Growthlab Solutions', 'location' => 'Cagayan de Oro', 'type' => 'Full-time', 'salary' => 'PHP 20,000–28,000', 'days' => '2'],
                ['title' => 'Medical Secretary', 'company' => 'Northern Mindanao Medical Center', 'location' => 'Cagayan de Oro', 'type' => 'Full-time', 'salary' => 'PHP 16,000–18,000', 'days' => '3'],
                ['title' => 'Customer Support Associate', 'company' => 'Teletech Inc.', 'location' => 'Cagayan de Oro', 'type' => 'Full-time', 'salary' => 'PHP 18,000–22,000', 'days' => '4'],
                ['title' => 'Administrative Officer I', 'company' => 'Provincial Government of MisOr', 'location' => 'Cagayan de Oro', 'type' => 'Government', 'salary' => 'PHP 22,000', 'days' => '5'],
                ['title' => 'Data Encoder', 'company' => 'City Government of CDO', 'location' => 'Cagayan de Oro', 'type' => 'Contract', 'salary' => 'PHP 15,000', 'days' => '7'],
            ] as $job)
            <div class="bg-white border border-gray-200 rounded-xl px-4 py-3 flex items-center gap-4 hover:border-green-200 transition-colors">
                <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-gray-900 truncate">{{ $job['title'] }}</div>
                    <div class="text-xs text-gray-400">{{ $job['company'] }} · {{ $job['location'] }} · {{ $job['days'] }}d ago</div>
                </div>
                <div class="hidden sm:flex items-center gap-2 flex-shrink-0">
                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-md">{{ $job['type'] }}</span>
                    <span class="text-xs text-gray-500">{{ $job['salary'] }}</span>
                </div>
                <a href="{{ route('register') }}"
                   class="flex-shrink-0 px-3 py-1.5 text-xs font-medium text-green-700 border border-green-200 rounded-lg hover:bg-green-50 transition-colors">
                    Apply
                </a>
            </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-5xl mx-auto px-4 pb-16">
        <div class="bg-green-700 rounded-2xl p-8 text-center">
            <h2 class="text-xl font-medium text-white mb-2">Ready to find your next job?</h2>
            <p class="text-sm text-green-200 mb-6">Create a free account and start applying to hundreds of jobs in Misamis Oriental today.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('register') }}"
                   class="px-6 py-2.5 text-sm font-medium text-green-700 bg-white rounded-lg hover:bg-green-50 transition-colors">
                    Create account — it's free
                </a>
                <a href="{{ route('login') }}"
                   class="px-6 py-2.5 text-sm font-medium text-white border border-green-500 rounded-lg hover:bg-green-600 transition-colors">
                    Already have an account
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-gray-200 bg-white">
        <div class="max-w-5xl mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 bg-green-700 rounded flex items-center justify-center">
                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    </svg>
                </div>
                <span class="text-xs text-gray-500">PESO-Link MisOr — Public Employment Service Office, Misamis Oriental</span>
            </div>
            <span class="text-xs text-gray-400">© {{ date('Y') }} All rights reserved</span>
        </div>
    </footer>

</body>
</html>