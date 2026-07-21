@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">✏️ Editar Tenant</h1>
    <p class="text-gray-500">{{ $tenant->name }}</p>
</div>

<form method="POST" action="{{ route('admin.tenants.update', $tenant) }}" class="bg-white rounded-lg shadow p-6 space-y-4 max-w-xl">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-sm font-medium text-gray-700">Nombre del Estudio</label>
        <input type="text" name="name" value="{{ old('name', $tenant->name) }}" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $tenant->slug) }}" required
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">RUC</label>
        <input type="text" name="ruc" value="{{ old('ruc', $tenant->ruc) }}" maxlength="11"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('ruc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email', $tenant->email) }}"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
        @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="inline-flex items-center">
            <input type="checkbox" name="activo" value="1" {{ old('activo', $tenant->activo) ? 'checked' : '' }}
                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <span class="ml-2 text-sm text-gray-600">Tenant activo</span>
        </label>
    </div>

    <div class="flex justify-end pt-4 space-x-3">
        <a href="{{ route('admin.tenants') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition">Cancelar</a>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
            💾 Guardar Cambios
        </button>
    </div>
</form>
@endsection