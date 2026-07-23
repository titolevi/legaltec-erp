@extends('layouts.app')

@section('content')
<div class="mb-6">
 <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">+ Nuevo Usuario</h1>
 <p class="text-gray-500 dark:text-gray-400">Crear usuario en el sistema</p>
</div>

<form method="POST" action="{{ route('admin.users.store') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-4 max-w-xl">
 @csrf

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre completo</label>
 <input type="text" name="name" value="{{ old('name') }}" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
 <input type="email" name="email" value="{{ old('email') }}" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
 <input type="password" name="password" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
 <input type="text" name="telefono" value="{{ old('telefono') }}"
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 </div>

 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rol</label>
 <select name="rol" required
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 <option value="">Seleccionar...</option>
 @foreach($roles as $value => $label)
 <option value="{{ $value }}" {{ old('rol') == $value ? 'selected' : '' }}>{{ $label }}</option>
 @endforeach
 </select>
 @error('rol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>

 @if($tenants->isNotEmpty())
 <div>
 <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tenant</label>
 <select name="tenant_id"
 class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
 <option value="">Legaltec (sin tenant)</option>
 @foreach($tenants as $t)
 <option value="{{ $t->id }}" {{ old('tenant_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
 @endforeach
 </select>
 @error('tenant_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
 </div>
 @endif

 <div class="flex justify-end pt-4 space-x-3">
 <a href="{{ route('admin.users') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">Cancelar</a>
 <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
 Crear Usuario
 </button>
 </div>
</form>
@endsection