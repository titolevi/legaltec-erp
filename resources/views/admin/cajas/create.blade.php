@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">💰 Nueva Caja</h1>
    <p class="text-gray-500 dark:text-gray-400">Crear una nueva caja en el tenant actual</p>
</div>

<form method="POST" action="{{ route('admin.cajas.store') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6 max-w-2xl">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre de la Caja</label>
            <input type="text" name="nombre" value="{{ old('nombre') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Ej: Caja Best Rent">
            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Slug</label>
            <input type="text" name="slug" value="{{ old('slug') }}" required
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Ej: best-rent">
            <p class="text-xs text-gray-400 mt-1">Solo letras, números y guiones</p>
            @error('slug') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descripción</label>
        <textarea name="descripcion" rows="2"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            placeholder="Propósito de esta caja...">{{ old('descripcion') }}</textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
            <select name="tipo" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="general" {{ old('tipo') == 'general' ? 'selected' : '' }}>General</option>
                <option value="movilidad" {{ old('tipo') == 'movilidad' ? 'selected' : '' }}>Movilidad</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Moneda</label>
            <select name="moneda" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="PEN" {{ old('moneda') == 'PEN' ? 'selected' : '' }}>S/ PEN</option>
                <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>$ USD</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Monto máximo</label>
            <input type="number" step="0.01" name="monto_maximo" value="{{ old('monto_maximo') }}"
                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                placeholder="Opcional">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Autorizadores</label>
            <div class="mt-1 space-y-2 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-md p-2">
                @forelse($usuarios as $u)
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" name="autorizadores[]" value="{{ $u->id }}" {{ in_array($u->id, old('autorizadores', [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="text-gray-700 dark:text-gray-300">{{ $u->name }} ({{ $u->rol }})</span>
                </label>
                @empty
                <p class="text-sm text-gray-400">No hay usuarios disponibles. Crea usuarios primero.</p>
                @endforelse
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cajeros</label>
            <div class="mt-1 space-y-2 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-md p-2">
                @forelse($usuarios as $u)
                <label class="flex items-center space-x-2 text-sm">
                    <input type="checkbox" name="cajeros[]" value="{{ $u->id }}" {{ in_array($u->id, old('cajeros', [])) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <span class="text-gray-700 dark:text-gray-300">{{ $u->name }} ({{ $u->rol }})</span>
                </label>
                @empty
                <p class="text-sm text-gray-400">No hay usuarios disponibles.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-4 space-x-3">
        <a href="{{ route('admin.cajas') }}" class="bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 px-4 py-2 rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 transition">Cancelar</a>
        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">💰 Crear Caja</button>
    </div>
</form>
@endsection