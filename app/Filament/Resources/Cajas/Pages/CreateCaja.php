<?php

namespace App\Filament\Resources\Cajas\Pages;

use App\Filament\Resources\Cajas\CajaResource;
use App\Models\Caja;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCaja extends CreateRecord
{
    protected static string $resource = CajaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function beforeCreate(): void
    {
        $tenantId = auth()->user()->tenant_id ?? session('impersonating_tenant_id');
        if (!$tenantId) {
            return;
        }

        $tenant = \App\Models\Tenant::find($tenantId);
        if (!$tenant) {
            return;
        }

        $count = Caja::where('tenant_id', $tenantId)->count();
        if ($count >= $tenant->max_cajas) {
            Notification::make()
                ->title("Limite alcanzado")
                ->body("Este tenant solo puede tener {$tenant->max_cajas} cajas como maximo.")
                ->danger()
                ->send();
            $this->halt();
        }
    }
}