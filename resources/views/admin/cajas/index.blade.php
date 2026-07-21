@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">💰 Cajas</h1>
    <p class="text-gray-500 dark:text-gray-400">Gestión de cajas del tenant</p>
</div>

@if(session('message'))
    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">{{ session('message') }}</div>
@endif

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Cajas registradas</h2>
        <a href="{{ route('admin.cajas.create') }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-indigo-700">➕ Nueva Caja</a>
    </div>

    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Caja</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tipo</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Autorizadores</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cajeros</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tickets</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($cajas as $caja)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-4 py-3">
                    <span class="text-lg">{{ $caja->icono }}</span>
                    <span class="ml-1 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $caja->nombre }}</span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($caja->tipo) }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                    @foreach($caja->autorizadores as $a)
                        <span class="inline-block bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs px-1.5 py-0.5 rounded mr-1">{{ $a->name }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">
                    @foreach($caja->cajeros as $c)
                        <span class="inline-block bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs px-1.5 py-0.5 rounded mr-1">{{ $c->name }}</span>
                    @endforeach
                </td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $caja->tickets_count }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full {{ $caja->activo ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                        {{ $caja->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm">
                    <a href="{{ route('admin.cajas.edit', $caja) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">✏️</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                    <div class="text-4xl mb-2">💰</div>
                    <p>No hay cajas registradas</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($cajas->hasPages())
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">{{ $cajas->links() }}</div>
    @endif
</div>
@endsection