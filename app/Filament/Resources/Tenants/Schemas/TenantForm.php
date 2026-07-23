<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nombre')->required(),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
                TextInput::make('ruc')->label('RUC'),
                TextInput::make('email')->label('Email')->email(),
                Select::make('status')->label('Estado')
                    ->options(['active' => 'Activo', 'suspended' => 'Suspendido', 'trial' => 'Prueba', 'cancelled' => 'Cancelado'])
                    ->required(),
                Select::make('plan')->label('Plan')
                    ->options(['trial' => 'Trial', 'starter' => 'Starter', 'professional' => 'Professional', 'enterprise' => 'Enterprise']),
                TextInput::make('mrr')->label('MRR')->numeric()->prefix('S/'),
                TextInput::make('max_users')->label('Max usuarios')->numeric()->default(10),
                TextInput::make('storage_limit')->label('Limite almacenamiento (MB)')->numeric()->default(1024),
                Toggle::make('activo')->label('Activo')->default(true),
                Toggle::make('maintenance_mode')->label('Modo mantenimiento'),
                Textarea::make('notas')->label('Notas')->columnSpanFull(),
            ]);
    }
}