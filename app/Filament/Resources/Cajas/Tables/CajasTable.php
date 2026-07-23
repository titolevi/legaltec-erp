<?php

namespace App\Filament\Resources\Cajas\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CajasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')->label('Nombre')->sortable()->searchable(),
                TextColumn::make('tipo')->label('Tipo')->badge(),
                TextColumn::make('moneda')->label('Moneda'),
                TextColumn::make('monto_maximo')->label('Max ticket')->money('PEN'),
                TextColumn::make('tickets_count')->label('Tickets')->counts('tickets'),
                ToggleColumn::make('activo')->label('Activa'),
            ])
            ->filters([])
            ->actions([EditAction::make()]);
    }
}