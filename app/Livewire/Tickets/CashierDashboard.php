<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class CashierDashboard extends Component
{
    use WithPagination;

    public $search = '';
    public $filtro_estado = '';

    public function render()
    {
        $query = Ticket::with(['cliente', 'asunto', 'usuario', 'autorizador']);

        if ($this->filtro_estado) {
            $query->where('estado', $this->filtro_estado);
        }

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('numero', 'like', '%' . $this->search . '%')
                  ->orWhere('detalle', 'like', '%' . $this->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'pendientes' => Ticket::where('estado', 'pendiente')->count(),
            'aprobados' => Ticket::where('estado', 'aprobado')->count(),
            'total' => Ticket::count(),
        ];

        return view('livewire.tickets.cashier-dashboard', compact('tickets', 'stats'))
            ->layout('layouts.app');
    }
}