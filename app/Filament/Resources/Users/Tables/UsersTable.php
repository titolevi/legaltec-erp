<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        $tenantId = session('impersonating_tenant_id');

        return $table
            ->modifyQueryUsing(fn ($query) => $tenantId ? $query->where('tenant_id', $tenantId) : $query)
            ->columns([
                TextColumn::make('name')->label('Nombre')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable()->searchable(),
                TextColumn::make('rol')->label('Rol')->badge()
                    ->color(fn ($state) => match ($state) {
                        'super_admin' => 'purple',
                        'support_admin' => 'blue',
                        'admin' => 'indigo',
                        'autorizador' => 'yellow',
                        'cajero' => 'green',
                        default => 'gray',
                    }),
                TextColumn::make('tenant.name')->label('Tenant'),
                ToggleColumn::make('activo')->label('Activo'),
                TextColumn::make('last_login_at')->label('Ultimo acceso')->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([EditAction::make()])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}