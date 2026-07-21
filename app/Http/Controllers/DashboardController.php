<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'pendientes' => Ticket::where('estado', 'pendiente')->count(),
            'aprobados' => Ticket::where('estado', 'aprobado')->count(),
            'total_mes' => Ticket::whereMonth('created_at', now()->month)->count(),
            'clientes' => Cliente::where('activo', true)->count(),
        ];

        return view('dashboard', compact('stats'));
    }
}