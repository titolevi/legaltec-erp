<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Legaltec ERP - @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-50">
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <span class="text-xl font-bold text-indigo-600">Legaltec ERP</span>
                    </div>
                    @auth
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-4">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            📊 Dashboard
                        </a>
                        <a href="{{ route('tickets.crear') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('tickets.crear') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            ➕ Nuevo Ticket
                        </a>
                        <a href="{{ route('tickets.aprobar') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('tickets.aprobar') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            ✅ Aprobar
                        </a>
                        <a href="{{ route('tickets.cajero') }}" class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('tickets.cajero') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:text-gray-700' }}">
                            💰 Cajero
                        </a>
                    </div>
                    @endauth
                </div>
                <div class="flex items-center">
                    @auth
                    <span class="text-sm text-gray-500 mr-4">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">Salir</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (session('message'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    {{ session('message') }}
                </div>
            @endif
            {{ $slot }}
        </div>
    </main>

    @livewireScripts
</body>
</html>