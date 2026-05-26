<x-guest-layout>
    <div class="w-full max-w-sm px-4">

        {{-- Logo & Branding --}}
        <div class="flex flex-col items-center mb-8">
            <div class="w-12 h-12 bg-green-700 rounded-xl flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v10a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                </svg>
            </div>
            <h1 class="text-xl font-medium text-gray-900">PESO-Link MisOr</h1>
            <p class="text-sm text-gray-500 mt-1">Employment Facilitation System</p>
        </div>

        {{-- Card --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">

            {{-- Tab Toggle --}}
            <div class="flex rounded-lg border border-gray-200 overflow-hidden mb-5">
                <a href="{{ route('login') }}"
                   class="flex-1 text-center py-2 text-sm font-medium {{ request()->routeIs('login') ? 'bg-green-700 text-white' : 'text-gray-500 hover:bg-gray-50' }} transition-colors">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="flex-1 text-center py-2 text-sm font-medium {{ request()->routeIs('register') ? 'bg-green-700 text-white' : 'text-gray-500 hover:bg-gray-50' }} transition-colors">
                    Register
                </a>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-3 py-2">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-xs text-gray-500 mb-1">Email address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="you@example.com">
                </div>

                {{-- Password --}}
                <div class="mb-4">
                    <label for="password" class="block text-xs text-gray-500 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                           class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                           placeholder="••••••••">
                </div>

                {{-- Remember & Forgot --}}
                <div class="flex items-center justify-between mb-5">
                    <label class="flex items-center gap-2 text-xs text-gray-500 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 focus:ring-blue-500">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs text-green-700 hover:underline">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-2 px-4 bg-green-700 hover:bg-green-800 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                    Sign in
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>
        </div>

        <p class="text-center text-xs text-gray-400 mt-5">PESO Office — Misamis Oriental</p>
    </div>
</x-guest-layout>
