<!DOCTYPE html>
<html lang="es" x-data="{ tema: '{{ auth()->user()->tema ?? 'claro' }}', menuOpen: false, modulosOpen: false }" :class="{ 'dark': tema === 'oscuro' }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Legaltec ERP - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
        .dropdown-enter { opacity: 0; transform: translateY(-4px); }
        .dropdown-active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
    <!-- Skip link -->
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-2 focus:left-2 focus:z-50 focus:bg-white focus:text-indigo-600 focus:px-4 focus:py-2 focus:rounded-md focus:shadow-lg">
        Saltar al contenido principal
    </a>

    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 transition-colors duration-200" role="navigation" aria-label="Navegación principal">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Left side -->
                <div class="flex items-center">
                    <!-- Logo -->
                    <a href="{{ route('admin.dashboard') }}" class="flex-shrink-0 flex items-center" aria-label="Ir al dashboard">
                        <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Legaltec ERP</span>
                    </a>

                    @auth
                    <!-- Desktop navigation -->
                    <div class="hidden sm:ml-8 sm:flex sm:items-center sm:space-x-1">
                        @if(auth()->user()->esSuperAdmin())
                        <!-- Dashboard -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md transition-colors
                                  {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                           aria-current="{{ request()->routeIs('admin.dashboard') ? 'page' : '' }}">
                            📊 Dashboard
                        </a>

                        <!-- Módulos dropdown -->
                        <div class="relative" @mouseenter="modulosOpen = true" @mouseleave="modulosOpen = false">
                            <button @click="modulosOpen = !modulosOpen"
                                    class="px-3 py-2 text-sm font-medium rounded-md transition-colors flex items-center
                                           {{ request()->routeIs('admin.tenants*') || request()->routeIs('admin.users*') || request()->routeIs('admin.cajas*') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700' }}"
                                    aria-haspopup="true" :aria-expanded="modulosOpen"
                                    aria-label="Módulos del sistema">
                                📦 Módulos
                                <svg class="ml-1.5 h-4 w-4 transition-transform" :class="{ 'rotate-180': modulosOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div x-show="modulosOpen"
                                 @click.away="modulosOpen = false"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="absolute left-0 pt-2 pb-1 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                                 role="menu" aria-label="Módulos disponibles">
                                <a href="{{ route('admin.tenants') }}"
                                   class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.tenants*') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="mr-3 text-lg">🏢</span>
                                    <div>
                                        <div class="font-medium">Tenants</div>
                                        <div class="text-xs text-gray-400">Gestionar estudios</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.users') }}"
                                   class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.users*') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="mr-3 text-lg">👥</span>
                                    <div>
                                        <div class="font-medium">Usuarios</div>
                                        <div class="text-xs text-gray-400">Roles y permisos</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.cajas') }}"
                                   class="flex items-center px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ request()->routeIs('admin.cajas*') ? 'bg-indigo-50 dark:bg-indigo-900 text-indigo-700 dark:text-indigo-300' : '' }}"
                                   role="menuitem">
                                    <span class="mr-3 text-lg">💰</span>
                                    <div>
                                        <div class="font-medium">Cajas</div>
                                        <div class="text-xs text-gray-400">Gestión de fondos</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endauth
                </div>

                <!-- Right side -->
                <div class="flex items-center space-x-3">
                    @auth
                    <!-- Dark mode toggle -->
                    <button @click="nuevoTema = (tema === 'claro' ? 'oscuro' : 'claro'); tema = nuevoTema; fetch('/tema', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }, body: JSON.stringify({tema: nuevoTema}) });"
                            class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                            x-cloak
                            :title="tema === 'claro' ? 'Modo oscuro' : 'Modo claro'"
                            aria-label="Cambiar tema">
                        <span x-show="tema === 'claro'" class="text-lg">🌙</span>
                        <span x-show="tema === 'oscuro'" class="text-lg">☀️</span>
                    </button>

                    <!-- User menu -->
                    <div class="relative" @click.away="menuOpen = false">
                        <button @click="menuOpen = !menuOpen"
                                class="flex items-center space-x-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-gray-100 px-3 py-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
                                aria-haspopup="true" :aria-expanded="menuOpen"
                                aria-label="Menú de usuario">
                            <span class="w-7 h-7 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center text-xs font-bold text-indigo-600 dark:text-indigo-400">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </span>
                            <span class="hidden sm:inline">{{ auth()->user()->name }}</span>
                            <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': menuOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="menuOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute right-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50"
                             role="menu" aria-label="Opciones de usuario">
                            <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Rol: <span class="font-medium">{{ str_replace('_', ' ', auth()->user()->rol) }}</span>
                                </p>
                            </div>
                            <a href="{{ route('profile') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">👤 Mi perfil</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100 dark:border-gray-700">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-50 dark:hover:bg-gray-700" role="menuitem">🚪 Cerrar sesión</button>
                            </form>
                        </div>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main id="main-content" class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <x-alert />
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </main>

    @livewireScripts
</body>
</html>