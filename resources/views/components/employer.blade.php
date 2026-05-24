<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — PESO-Link MisOr</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased min-h-screen flex">

    {{-- Sidebar --}}
    <aside class="w-52 bg-white border-r border-gray-200 flex flex-col min-h-screen fixed top-0 left-0 z-20">
        <div class="px-4 py-4 border-b border-gray-200">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 bg-blue-700 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                    </svg>
                </div>
                <div>
                    <div class="text-xs font-medium text-gray-900 leading-tight">PESO-Link MisOr</div>
                    <div class="text-xs text-gray-400 leading-tight">Employer portal</div>
                </div>
            </div>
        </div>

        <nav class="flex-1 py-2">
            <a href="{{ route('employer.dashboard') }}"
               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('employer.dashboard') ? 'text-blue-700 bg-blue-50 font-medium' : 'text-gray-500 hover:bg-gray-50' }} transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('employer.jobs.create') }}"
               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('employer.jobs.create') ? 'text-blue-700 bg-blue-50 font-medium' : 'text-gray-500 hover:bg-gray-50' }} transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Post a job
            </a>
            <a href="{{ route('employer.jobs.index') }}"
               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('employer.jobs.index') ? 'text-blue-700 bg-blue-50 font-medium' : 'text-gray-500 hover:bg-gray-50' }} transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                </svg>
                My job posts
            </a>
            <a href="{{ route('employer.applications.index') }}"
               class="flex items-center gap-2.5 px-4 py-2 text-sm {{ request()->routeIs('employer.applications*') ? 'text-blue-700 bg-blue-50 font-medium' : 'text-gray-500 hover:bg-gray-50' }} transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                </svg>
                Applications
            </a>
        </nav>

        <div class="p-3 border-t border-gray-200">
            <div class="flex items-center gap-2 mb-2 px-1">
                <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium text-blue-700 flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 2)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'Employer' }}</div>
                    <div class="text-xs text-gray-400 truncate">Employer</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 px-2 py-1.5 text-xs text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                    Sign out
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="ml-52 flex-1 min-h-screen">
        {{ $slot }}
    </main>

</body>
</html>