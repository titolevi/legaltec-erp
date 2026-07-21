<?php

namespace App\Livewire\Tickets;

use App\Models\Ticket;
use Livewire\Component;
use Livewire\WithPagination;

class ApproveTicket extends Component
{
    use WithPagination;

    public function approve($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['estado' => 'aprobado']);
        session()->flash('message', '✅ Ticket ' . $ticket->numero . ' aprobado.');
    }

    public function reject($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->update(['estado' => 'rechazado']);
        session()->flash('message', '❌ Ticket ' . $ticket->numero . ' rechazado.');
    }

    public function render()
    {
        $tickets = Ticket::where('autorizador_id', auth()->id())
            ->where('estado', 'pendiente')
            ->with(['cliente', 'asunto', 'usuario'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.tickets.approve-ticket', compact('tickets'))
            ->layout('layouts.app');
    }
}