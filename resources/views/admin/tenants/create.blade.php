@extends('layouts.app')

@section('content')
<div class="mb-6">
 <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">+ Nuevo Tenant</h1>
 <p class="text-gray-500 dark:text-gray-400">Crear un nuevo estudio de abogados en el SaaS</p>
</div>

<form method="POST" action="{{ route('admin.tenants.store') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4 max-w-xl">
 @csrf

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del Estudio</label>
 <input type="text" name="name" value="{{ old('name') }}" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 placeholder="Ej: Viera Abogados">
 @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug (identificador único)</label>
 <input type="text" name="slug" value="{{ old('slug') }}" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 placeholder="Ej: viera">
 <p class="text-xs text-gray-400 mt-1">Solo letras, números y guiones. Sin espacios.</p>
 @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">RUC (opcional)</label>
 <input type="text" name="ruc" value="{{ old('ruc') }}" maxlength="11"
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 placeholder="Ej: 20123456789">
 @error('ruc') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email de contacto (opcional)</label>
 <input type="email" name="email" value="{{ old('email') }}"
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
 placeholder="Ej: contacto@vieraabogados.pe">
 @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div class="flex justify-end pt-4 space-x-3">
 <a href="{{ route('admin.tenants') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">Cancelar</a>
 <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
 Crear Tenant
 </button>
 </div>
</form>
@endsection