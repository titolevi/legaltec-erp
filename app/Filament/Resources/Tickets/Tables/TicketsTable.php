<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Models\Caja;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('caja.nombre')->label('Caja')->sortable(),
                TextColumn::make('concepto')->label('Concepto')->searchable(),
                TextColumn::make('monto')->label('Monto')->money(fn ($record) => $record->moneda)->sortable(),
                TextColumn::make('moneda')->label('Moneda'),
                TextColumn::make('status')->label('Estado')->badge()
                    ->color(fn ($state) => match ($state) {
                        'pendiente' => 'warning',
                        'aprobado' => 'success',
                        'rechazado' => 'danger',
                        'atendido' => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')->label('Creado por'),
                TextColumn::make('autorizador.name')->label('Autorizador'),
                TextColumn::make('cajero.name')->label('Cajero'),
                TextColumn::make('created_at')->label('Creado')->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([EditAction::make()]);
    }
}
