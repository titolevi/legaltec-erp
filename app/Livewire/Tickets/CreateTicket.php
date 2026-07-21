<?php

namespace App\Livewire\Tickets;

use App\Models\Cliente;
use App\Models\Asunto;
use App\Models\Ticket;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

class CreateTicket extends Component
{
    #[Validate('required|exists:clientes,id')]
    public $cliente_id = '';

    #[Validate('required|exists:asuntos,id')]
    public $asunto_id = '';

    public $codigo_asunto = '';

    #[Validate('required|date')]
    public $fecha_diligencia = '';

    #[Validate('required|string|max:1000')]
    public $detalle = '';

    public $distrito = '';

    public $facturable = true;
    public $monto = 0;
    public $moneda = 'PEN';

    #[Validate('required|string|max:255')]
    public $ejecutado_por = '';

    #[Validate('required|exists:users,id')]
    public $autorizador_id = '';

    #[Computed]
    public function clientes()
    {
        return Cliente::where('activo', true)->orderBy('nombre')->get();
    }

    #[Computed]
    public function asuntos()
    {
        if (!$this->cliente_id) {
            return collect();
        }
        return Asunto::where('cliente_id', $this->cliente_id)
                     ->where('activo', true)
                     ->orderBy('nombre')
                     ->get();
    }

    public function updatedClienteId()
    {
        $this->asunto_id = '';
        $this->codigo_asunto = '';
    }

    public function updatedAsuntoId()
    {
        if ($this->asunto_id) {
            $asunto = Asunto::find($this->asunto_id);
            $this->codigo_asunto = $asunto?->codigo ?? '';
        }
    }

    public function save()
    {
        $this->validate();

        $ultimo = Ticket::withTrashed()->max('id') ?? 0;
        $numero = 'TKT-' . str_pad($ultimo + 1, 6, '0', STR_PAD_LEFT);

        Ticket::create([
            'numero' => $numero,
            'cliente_id' => $this->cliente_id,
            'asunto_id' => $this->asunto_id,
            'codigo_asunto' => $this->codigo_asunto,
            'fecha_diligencia' => $this->fecha_diligencia,
            'detalle' => $this->detalle,
            'distrito' => $this->distrito,
            'facturable' => $this->facturable,
            'monto' => $this->monto,
            'moneda' => $this->moneda,
            'ejecutado_por' => $this->ejecutado_por,
            'autorizador_id' => $this->autorizador_id,
            'estado' => 'pendiente',
            'usuario_id' => auth()->id(),
        ]);

        session()->flash('message', '✅ Ticket creado exitosamente. Número: ' . $numero);
        $this->reset();
    }

    public function render()
    {
        return view('livewire.tickets.create-ticket')
            ->layout('layouts.app');
    }
}