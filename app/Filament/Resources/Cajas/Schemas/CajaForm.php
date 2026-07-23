<?php

namespace App\Filament\Resources\Cajas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CajaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('nombre')->label('Nombre')->required(),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
                Textarea::make('descripcion')->label('Descripcion'),
                Select::make('tipo')->label('Tipo')
                    ->options(['general' => 'General', 'movilidad' => 'Movilidad'])
                    ->required(),
                TextInput::make('monto_maximo')->label('Monto maximo por ticket')->numeric()->nullable()->helperText('Limite maximo que puede tener un ticket en esta caja'),
                Toggle::make('activo')->label('Activa')->default(true),
            ]);
    }
}