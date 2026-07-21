@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">🏢 Panel de Administración</h1>
    <p class="text-gray-500">Gestión de Tenants — Legaltec SaaS</p>
</div>

@if(session('message'))
    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
        {{ session('message') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-4 border-b flex justify-between items-center">
        <h2 class="text-lg font-semibold">Tenants registrados</h2>
        <a href="{{ route('admin.tenants.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            ➕ Nuevo Tenant
        </a>
    </div>

    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RUC</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Usuarios</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($tenants as $tenant)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $tenant->name }}</td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $tenant->slug }}</td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $tenant->ruc ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-gray-500">{{ $tenant->users_count }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded-full {{ $tenant->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $tenant->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm space-x-2 flex">
                    <a href="{{ route('admin.tenants.enter', $tenant) }}"
                       class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition text-xs">
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
                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                    <div class="text-4xl mb-4">🏢</div>
                    <p>No hay tenants registrados aún</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tenants->hasPages())
    <div class="p-4 border-t">
        {{ $tenants->links() }}
    </div>
    @endif
</div>
@endsection