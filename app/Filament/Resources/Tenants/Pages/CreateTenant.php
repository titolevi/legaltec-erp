<?php

namespace App\Filament\Resources\Tenants\Pages;

use App\Filament\Resources\Tenants\TenantResource;
use App\Models\User;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $data = $this->form->getRawState();

        if (!empty($data['admin_name']) && !empty($data['admin_email']) && !empty($data['admin_password'])) {
            User::create([
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'password' => bcrypt($data['admin_password']),
                'rol' => 'admin',
                'tenant_id' => $this->record->id,
                'activo' => true,
            ]);
        }
    }
}