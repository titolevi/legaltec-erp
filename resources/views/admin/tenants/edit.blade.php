@extends('layouts.app')

@section('content')
<div class="mb-6">
 <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Editar Editar Tenant</h1>
 <p class="text-gray-500 dark:text-gray-400">{{ $tenant->name }}</p>
</div>

<form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4 max-w-xl">
 @csrf
 @method('PUT')

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Estudio</label>
 <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
 <input type="text" name="slug" value="{{ old('slug', $tenant->slug) }}" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">RUC</label>
 <input type="text" name="ruc" value="{{ old('ruc', $tenant->ruc) }}" maxlength="11"
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
 <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 </div>

 <div class="grid grid-cols-2 gap-4">
 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
 <select name="status" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 <option value="active" {{ old('status', $tenant->status) == 'active' ? 'selected' : '' }}>Activo</option>
 <option value="suspended" {{ old('status', $tenant->status) == 'suspended' ? 'selected' : '' }}>Suspendido</option>
 <option value="trial" {{ old('status', $tenant->status) == 'trial' ? 'selected' : '' }}>Trial</option>
 <option value="cancelled" {{ old('status', $tenant->status) == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
 </select>
 </div>
 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Plan</label>
 <select name="plan" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 <option value="trial" {{ old('plan', $tenant->plan) == 'trial' ? 'selected' : '' }}>Trial</option>
 <option value="starter" {{ old('plan', $tenant->plan) == 'starter' ? 'selected' : '' }}>Starter</option>
 <option value="professional" {{ old('plan', $tenant->plan) == 'professional' ? 'selected' : '' }}>Professional</option>
 <option value="enterprise" {{ old('plan', $tenant->plan) == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
 </select>
 </div>
 </div>

 <div class="flex justify-end pt-4 space-x-3">
 <a href="{{ route('admin.tenants') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">Cancelar</a>
 <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition"> Guardar Cambios</button>
 </div>
</form>
@endsection