<?php

namespace App\Filament\Resources\Tickets\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TicketForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('caja_id')->label('Caja')
                    ->relationship('caja', 'nombre')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('autorizador_id', null)),
                TextInput::make('concepto')->label('Concepto')->required()->maxLength(255),
                TextInput::make('monto')->label('Monto')->required()->numeric()->minValue(0.01),
                Select::make('moneda')->label('Moneda')
                    ->options(['PEN' => 'S/ PEN', 'USD' => '$ USD'])
                    ->required(),
                Textarea::make('notas')->label('Notas')->columnSpanFull(),
            ]);
    }
}
