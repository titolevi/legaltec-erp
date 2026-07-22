@extends('layouts.app')

@section('content')
<x-alert />
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">🏢 Panel de Tenants</h1>
    <p class="text-gray-500 dark:text-gray-400">Gestión de Tenants — Legaltec SaaS</p>
</div>


<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tenants registrados</h2>
        <a href="{{ route('admin.tenants.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            ➕ Nuevo Tenant
        </a>
    </div>

    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tenant</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Slug</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Plan</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Usuarios</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">MRR</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($tenants as $tenant)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $tenant->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $tenant->slug }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($tenant->plan) }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $tenant->users_count }} / {{ $tenant->max_users }}</td>
                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">S/ {{ number_format($tenant->mrr, 2) }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($tenant->status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                        @elseif($tenant->status == 'suspended') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                        @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                        @endif">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm space-x-2">
                    <a href="{{ route('admin.tenants.enter', $tenant) }}"
                       class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition text-xs">
                        🔀 Entrar
                    </a>
                    <a href="{{ route('admin.tenants.edit', $tenant) }}"
                       class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition text-xs">
                        ✏️ Editar
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                    <div class="text-4xl mb-4">🏢</div>
                    <p>No hay tenants registrados aún</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tenants->hasPages())
    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        {{ $tenants->links() }}
    </div>
</div>
@endsection