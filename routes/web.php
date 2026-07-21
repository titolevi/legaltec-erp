<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tickets
    Route::get('/tickets/crear', \App\Livewire\Tickets\CreateTicket::class)->name('tickets.crear');
    Route::get('/tickets/aprobar', \App\Livewire\Tickets\ApproveTicket::class)->name('tickets.aprobar');
    Route::get('/tickets/cajero', \App\Livewire\Tickets\CashierDashboard::class)->name('tickets.cajero');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';