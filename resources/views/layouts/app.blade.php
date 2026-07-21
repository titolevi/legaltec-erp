<!DOCTYPE html>
<html lang="es" x-data="{ tema: '{{ auth()->user()->tema ?? 'claro' }}' }" x-init="document.documentElement.classList.toggle('dark', tema === 'oscuro')" :class="{ 'dark': tema === 'oscuro' }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Legaltec ERP - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Legaltec ERP</span>
                    </div>
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4">
                        @if(auth()->user()->esSuperAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.tenants*') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('admin.tenants') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.tenants*') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            🏢 Tenants
                        </a>
                        @endif
                    </div>
                    @endauth
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                    <!-- Dark mode toggle -->
                    <button @click="nuevoTema = (tema === 'claro' ? 'oscuro' : 'claro'); tema = nuevoTema; fetch('/tema', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: JSON.stringify({tema: nuevoTema}) });"
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            x-cloak
                            :title="tema === 'claro' ? 'Modo oscuro' : 'Modo claro'"
                            aria-label="Cambiar tema">
                        <span x-show="tema === 'claro'" class="text-lg">🌙</span>
                        <span x-show="tema === 'oscuro'" class="text-lg">☀️</span>
                    </button>
                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Salir</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('message'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </main>

    @livewireScripts
</body>
</html>