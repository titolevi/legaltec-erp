<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->name('dashboard')->middleware(['auth']);

    // Logout (Breeze/Volt no define ruta con nombre)
    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Entrar a un tenant (contexto multi-tenant)
    Route::get('/panel/tenant/{slug}', function ($slug) {
        $tenant = \App\Models\Tenant::where('slug', $slug)->firstOrFail();
        session(['impersonating_tenant_id' => $tenant->id]);
        return redirect('/panel');
    })->name('tenant.enter');

    // Admin → Solo super_admin y admin
    Route::prefix('admin')->name('admin.')->middleware(['role:super_admin,admin'])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'index'])->name('tenants');
        Route::get('/tenants/crear', [\App\Http\Controllers\Admin\TenantController::class, 'create'])->name('tenants.create');
        Route::post('/tenants', [\App\Http\Controllers\Admin\TenantController::class, 'store'])->name('tenants.store');
        Route::get('/tenants/{tenant}/editar', [\App\Http\Controllers\Admin\TenantController::class, 'edit'])->name('tenants.edit');
        Route::put('/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'update'])->name('tenants.update');
        Route::delete('/tenants/{tenant}', [\App\Http\Controllers\Admin\TenantController::class, 'destroy'])->name('tenants.destroy');
        Route::get('/tenants/{tenant}/entrar', [\App\Http\Controllers\Admin\TenantController::class, 'enter'])->name('tenants.enter');
        Route::get('/tenants/salir', [\App\Http\Controllers\Admin\TenantController::class, 'exit'])->name('tenants.exit');

        // Users
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
        Route::get('/users/crear', [\App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/editar', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');

        // Cajas
        Route::get('/cajas', [\App\Http\Controllers\Admin\CajaController::class, 'index'])->name('cajas');
        Route::get('/cajas/crear', [\App\Http\Controllers\Admin\CajaController::class, 'create'])->name('cajas.create');
        Route::post('/cajas', [\App\Http\Controllers\Admin\CajaController::class, 'store'])->name('cajas.store');
        Route::get('/cajas/{caja}/editar', [\App\Http\Controllers\Admin\CajaController::class, 'edit'])->name('cajas.edit');
        Route::put('/cajas/{caja}', [\App\Http\Controllers\Admin\CajaController::class, 'update'])->name('cajas.update');
        Route::delete('/cajas/{caja}', [\App\Http\Controllers\Admin\CajaController::class, 'destroy'])->name('cajas.destroy');
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