@extends('layouts.app')

@section('content')
<div class="mb-6">
 <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100"> Panel de Administración Legaltec</h1>
 <p class="text-gray-500 dark:text-gray-400">Gestión global del SaaS — Tenants, métricas y salud del sistema</p>
 </div>

 @if(session('message'))
 <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">{{ session('message') }}</div>
 @endif

 <!-- Stats Cards -->
 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-indigo-500">
 <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">{{ $stats['total_tenants'] }}</div>
 <div class="text-sm text-gray-500 dark:text-gray-400">Tenants activos</div>
 </div>
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-blue-500">
 <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $stats['total_users'] }}</div>
 <div class="text-sm text-gray-500 dark:text-gray-400">Usuarios totales</div>
 </div>
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 border-green-500">
 <div class="text-3xl font-bold text-green-600 dark:text-green-400">S/ {{ number_format($stats['mrr_total'], 2) }}</div>
 <div class="text-sm text-gray-500 dark:text-gray-400">MRR</div>
 </div>
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 border-l-4 {{ $stats['alertas_criticas'] > 0 ? 'border-red-500' : 'border-gray-300 dark:border-gray-600' }}">
 <div class="text-3xl font-bold {{ $stats['alertas_criticas'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500' }}">{{ $stats['alertas_criticas'] }}</div>
 <div class="text-sm text-gray-500 dark:text-gray-400">Alertas críticas</div>
 </div>
 </div>

 <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
 <!-- Usage Stats -->
 <div class="lg:col-span-2">
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
 <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"> Uso Global del Mes</h2>
 <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
 <div>
 <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['usage_global']->total_usuarios ?? 0 }}</div>
 <div class="text-xs text-gray-500 dark:text-gray-400">Usuarios activos</div>
 </div>
 <div>
 <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $stats['usage_global']->total_tickets ?? 0 }}</div>
 <div class="text-xs text-gray-500 dark:text-gray-400">Tickets creados</div>
 </div>
 <div>
 <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format(($stats['usage_global']->total_storage ?? 0) / 1024, 1) }} GB</div>
 <div class="text-xs text-gray-500 dark:text-gray-400">Almacenamiento usado</div>
 </div>
 <div>
 <div class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($stats['usage_global']->total_api ?? 0) }}</div>
 <div class="text-xs text-gray-500 dark:text-gray-400">API calls</div>
 </div>
 </div>
 </div>

 <!-- Tenants Table -->
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
 <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
 <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100"> Tenants</h2>
 <a href="{{ route('admin.tenants.create') }}" class="bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm hover:bg-indigo-700">+ Nuevo</a>
 </div>
 <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
 <thead class="bg-gray-50 dark:bg-gray-900">
 <tr>
 <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Tenant</th>
 <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Plan</th>
 <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Usuarios</th>
 <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
 <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">MRR</th>
 <th class="px-4 py-2"></th>
 </tr>
 </thead>
 <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
 @forelse($tenants as $tenant)
 <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
 <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $tenant->name }}</td>
 <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($tenant->plan) }}</td>
 <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $tenant->users_count }} / {{ $tenant->max_users }}</td>
 <td class="px-4 py-2">
 <span class="px-2 py-0.5 text-xs rounded-full
 @if($tenant->status == 'active') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
 @elseif($tenant->status == 'suspended') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
 @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 @endif">
 {{ ucfirst($tenant->status) }}
 </span>
 </td>
 <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100">S/ {{ number_format($tenant->mrr, 2) }}</td>
 <td class="px-4 py-2 text-sm">
 <a href="{{ route('admin.tenants.enter', $tenant) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"> Entrar</a>
 </td>
 </tr>
 @empty
 <tr>
 <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No hay tenants registrados</td>
 </tr>
 @endforelse
 </tbody>
 </table>
 </div>
 </div>

 <!-- Sidebar -->
 <div class="space-y-6">
 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
 <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"> Salud del Sistema</h2>
 @forelse($stats['health_checks'] as $check)
 <div class="flex justify-between items-center py-1">
 <span class="text-sm text-gray-700 dark:text-gray-300">{{ $check->tipo }}</span>
 <span class="px-2 py-0.5 text-xs rounded-full
 @if($check->estado == 'healthy') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
 @elseif($check->estado == 'warning') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
 @else bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 @endif">
 {{ $check->estado }}
 </span>
 </div>
 @empty
 <p class="text-sm text-gray-400 dark:text-gray-500">Sin datos de salud</p>
 @endforelse
 </div>

 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
 <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4"> Auditoría Reciente</h2>
 <div class="space-y-2">
 @forelse($stats['audit_logs'] as $log)
 <div class="text-sm border-b border-gray-100 dark:border-gray-700 pb-1">
 <span class="font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Sistema' }}</span>
 <span class="text-gray-500 dark:text-gray-400"> {{ $log->descripcion }}</span>
 <div class="text-xs text-gray-400 dark:text-gray-500">{{ $log->created_at->diffForHumans() }}</div>
 </div>
 @empty
 <p class="text-sm text-gray-400 dark:text-gray-500">Sin actividad registrada</p>
 @endforelse
 </div>
 </div>

 <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
 <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">🆕 Tenants Recientes</h2>
 <div class="space-y-2">
 @forelse($stats['tenants_recientes'] as $t)
 <div class="flex justify-between text-sm">
 <span class="text-gray-700 dark:text-gray-300">{{ $t->name }}</span>
 <span class="text-gray-400 dark:text-gray-500">{{ $t->created_at->format('d/m/Y') }}</span>
 </div>
 @empty
 <p class="text-sm text-gray-400 dark:text-gray-500">Sin tenants</p>
 @endforelse
 </div>
 </div>
 </div>
 </div>
 </div>
 </div>
</div>
@endsection