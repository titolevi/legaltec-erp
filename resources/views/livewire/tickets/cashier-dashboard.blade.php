<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">💰 Panel del Cajero</h1>
        <p class="text-gray-500">Todas las solicitudes de movilidad e impresiones</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-yellow-500">{{ $stats['pendientes'] }}</div>
            <div class="text-sm text-gray-500">Pendientes</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-green-500">{{ $stats['aprobados'] }}</div>
            <div class="text-sm text-gray-500">Aprobados</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-2xl font-bold text-blue-500">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-500">Total tickets</div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <div class="flex flex-wrap gap-4">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="🔍 Buscar ticket o detalle..."
                class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            <select wire:model.live="filtro_estado" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">Todos</option>
                <option value="pendiente">Pendientes</option>
                <option value="aprobado">Aprobados</option>
                <option value="rechazado">Rechazados</option>
                <option value="completado">Completados</option>
            </select>
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($tickets->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Asunto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detalle</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ticket->numero }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $ticket->cliente->nombre }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $ticket->codigo_asunto }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 max-w-xs truncate">{{ $ticket->detalle }}</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">S/ {{ number_format($ticket->monto, 2) }}</td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($ticket->estado == 'pendiente') bg-yellow-100 text-yellow-800
                                @elseif($ticket->estado == 'aprobado') bg-green-100 text-green-800
                                @elseif($ticket->estado == 'rechazado') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($ticket->estado) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $ticket->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <div class="text-4xl mb-4">📭</div>
                <p class="text-lg">No hay tickets registrados</p>
            </div>
        @endif
    </div>
</div>