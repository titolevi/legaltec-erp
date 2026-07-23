<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nombre')->required(),
                TextInput::make('email')->label('Email')->required()->email()->unique(ignoreRecord: true),
                TextInput::make('password')->label('Contrasena')->password()
                    ->required(fn ($context) => $context === 'create'),
                Select::make('rol')->label('Rol')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'support_admin' => 'Support Admin',
                        'support_agent' => 'Support Agent',
                        'admin' => 'Admin',
                        'autorizador' => 'Autorizador',
                        'cajero' => 'Cajero',
                        'usuario' => 'Usuario',
                    ])->required(),
                Select::make('tenant_id')->label('Tenant')
                    ->relationship('tenant', 'name')
                    ->nullable(),
                Toggle::make('activo')->label('Activo')->default(true),
            ]);
    }
}