<?php

use App\Http\Controllers\DashboardController;
use App\Livewire\Tickets\CreateTicket;
use App\Livewire\Tickets\CashierDashboard;
use App\Livewire\Tickets\ApproveTicket;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tickets
    Route::get('/tickets/crear', CreateTicket::class)->name('tickets.crear');
    Route::get('/tickets/aprobar', ApproveTicket::class)->name('tickets.aprobar');
    Route::get('/tickets/cajero', CashierDashboard::class)->name('tickets.cajero');
    Route::get('/tickets/{ticket}', [App\Livewire\Tickets\TicketDetail::class, '__invoke'])->name('tickets.detalle');
});