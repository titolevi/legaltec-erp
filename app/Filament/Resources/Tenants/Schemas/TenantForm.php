<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('name')
                    ->columnSpanFull(),
                Textarea::make('slug')
                    ->columnSpanFull(),
                Textarea::make('ruc')
                    ->columnSpanFull(),
                Textarea::make('email')
                    ->label('Email address')
                    ->columnSpanFull(),
                Textarea::make('logo')
                    ->columnSpanFull(),
                TextInput::make('activo')
                    ->numeric()
                    ->default(1),
                Textarea::make('config')
                    ->columnSpanFull(),
                Textarea::make('status')
                    ->default('"active"')
                    ->columnSpanFull(),
                Textarea::make('plan')
                    ->default('"trial"')
                    ->columnSpanFull(),
                TextInput::make('mrr')
                    ->numeric()
                    ->default(0),
                TextInput::make('storage_limit')
                    ->numeric()
                    ->default(1024),
                TextInput::make('storage_used')
                    ->numeric()
                    ->default(0),
                TextInput::make('max_users')
                    ->numeric()
                    ->default(10),
                TextInput::make('maintenance_mode')
                    ->numeric()
                    ->default(0),
                Textarea::make('maintenance_message')
                    ->columnSpanFull(),
                Textarea::make('notas')
                    ->columnSpanFull(),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
