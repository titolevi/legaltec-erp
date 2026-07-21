<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout (Breeze/Volt no define ruta con nombre)
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Admin → Solo super_admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'index'])->name('tenants');
        Route::get('/tenants/crear', [\App\Http\Controllers\Admin\TenantController::class, 'create'])->name('tenants.create');
        Route::post('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}/editar', [\App\Http\Controllers\Admin\TenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'update'])->name('tenants.update');
        Route::delete('/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'destroy'])->name('tenants.destroy');
        Route::post('/tenants/{tenant}/entrar', [\App\Http\Controllers\Admin\TenantController::class, 'enter'])->name('tenants.enter');
        Route::post('/tenants/salir', [\App\Http\Controllers\Admin\TenantController::class, 'exit'])->name('tenants.exit');
    });

    // Tickets
    Route::get('/tickets/crear', \App\Livewire\Tickets\CreateTicket::class)->name('tickets.crear');
    Route::get('/tickets/aprobar', \App\Livewire\Tickets\ApproveTicket::class)->name('tickets.aprobar');
    Route::get('/tickets/cajero', \App\Livewire\Tickets\CashierDashboard::class)->name('tickets.cajero');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';