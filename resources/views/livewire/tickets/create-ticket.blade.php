<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">➕ Nuevo Ticket</h1>
        <p class="text-gray-500">Solicitud de movilidad, impresión o gasto</p>
    </div>

    <form wire:submit="save" class="bg-white rounded-lg shadow p-6 space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Cliente -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Cliente</label>
                <select wire:model.live="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccionar...</option>
                    @foreach ($this->clientes as $cliente)
                        <option value="{{ $cliente->id }}">{{ $cliente->codigo }} - {{ $cliente->nombre }}</option>
                    @endforeach
                </select>
                @error('cliente_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Asunto -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Asunto</label>
                <select wire:model.live="asunto_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccionar...</option>
                    @foreach ($this->asuntos as $asunto)
                        <option value="{{ $asunto->id }}">{{ $asunto->codigo }} - {{ $asunto->nombre }}</option>
                    @endforeach
                </select>
                @error('asunto_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Fecha -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha de diligencia</label>
                <input type="date" wire:model="fecha_diligencia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('fecha_diligencia') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Distrito -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Distrito</label>
                <input type="text" wire:model="distrito" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Ej: Lima, Miraflores...">
            </div>

            <!-- Detalle -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700">Detalle de la diligencia</label>
                <textarea wire:model="detalle" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Describa la gestión realizada..."></textarea>
                @error('detalle') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Monto -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Monto</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <select wire:model="moneda" class="rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3">
                        <option value="PEN">S/</option>
                        <option value="USD">$</option>
                    </select>
                    <input type="number" step="0.01" wire:model="monto" class="block w-full rounded-r-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00">
                </div>
            </div>

            <!-- Facturable -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Facturable</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" wire:model="facturable" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-gray-600">Sí, este gasto es facturable</span>
                    </label>
                </div>
            </div>

            <!-- Ejecutado por -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Ejecutado por</label>
                <input type="text" wire:model="ejecutado_por" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nombre de quien realizó la diligencia">
                @error('ejecutado_por') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <!-- Autorizador -->
            <div>
                <label class="block text-sm font-medium text-gray-700">Autorizador</label>
                <select wire:model="autorizador_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Seleccionar...</option>
                    @foreach (\App\Models\User::where('rol', 'autorizador')->orWhere('rol', 'admin')->get() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('autorizador_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                🚀 Crear Ticket
            </button>
        </div>
    </form>
</div>