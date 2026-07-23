<?php

namespace App\Filament\Resources\Tenants\Tables;

use App\Models\Tenant;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Session;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Tenant')->sortable()->searchable(),
                TextColumn::make('slug')->label('Slug')->sortable()->searchable(),
                TextColumn::make('plan')->label('Plan')->badge(),
                TextColumn::make('status')->label('Estado')->badge()
                    ->color(fn ($state) => match ($state) {
                        'active' => 'success',
                        'suspended' => 'danger',
                        'trial' => 'warning',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('mrr')->label('MRR')->money('PEN'),
                TextColumn::make('max_cajas')->label('Cajas'),
                TextColumn::make('max_users')->label('Usuarios'),
                ToggleColumn::make('activo')->label('Activo'),
            ])
            ->filters([])
            ->actions([
                Action::make('entrar')
                    ->label('Entrar')
                    ->icon('heroicon-m-arrow-right-end-on-rectangle')
                    ->color('success')
                    ->action(function (Tenant $record) {
                        session(['impersonating_tenant_id' => $record->id]);
                        return redirect()->to('/panel/tenant/' . $record->slug);
                    }),
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}