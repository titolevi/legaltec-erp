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
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'index'])->name('tenants');
        Route::get('/tenants/crear', [\App\Http\Controllers\Admin\TenantController::class, 'create'])->name('tenants.create');
        Route::post('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}/editar', [\App\Http\Controllers\Admin\TenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'update'])->name('tenants.update');
        Route::delete('/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'destroy'])->name('tenants.destroy');
        Route::get('/tenants/{tenant}/entrar', [\App\Http\Controllers\Admin\TenantController::class, 'enter'])->name('tenants.enter');
        Route::get('/tenants/salir', [\App\Http\Controllers\Admin\TenantController::class, 'exit'])->name('tenants.exit');
    });

    // Theme toggle
    Route::post('/tema', function (\Illuminate\Http\Request $request) {
        $tema = $request->validate(['tema' => 'required|in:claro,oscuro']);
        auth()->user()->setPreferencia('tema', $tema['tema']);
        return response()->json(['status' => 'ok']);
    })->name('tema.toggle');
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';