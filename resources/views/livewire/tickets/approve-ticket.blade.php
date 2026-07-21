<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">✅ Aprobar Tickets</h1>
        <p class="text-gray-500">Tickets pendientes de tu autorización</p>
    </div>

    @if(session('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($tickets->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ticket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detalle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Solicitante</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tickets as $ticket)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ticket->numero }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->cliente->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">{{ $ticket->detalle }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">S/ {{ number_format($ticket->monto, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $ticket->usuario->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <button wire:click="approve({{ $ticket->id }})" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                ✅ Aprobar
                            </button>
                            <button wire:click="reject({{ $ticket->id }})" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                ❌ Rechazar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $tickets->links() }}
            </div>
        @else
            <div class="p-12 text-center text-gray-500">
                <div class="text-4xl mb-4">🎉</div>
                <p class="text-lg">No hay tickets pendientes de aprobación</p>
            </div>
        @endif
    </div>
</div>