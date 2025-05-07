<?php

namespace App\Filament\Resources\User\CategoryResource\Pages;

use App\Filament\Resources\User\CategoryResource;
use App\Filament\Resources\User\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions for users
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                // ... existing code ...
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Lihat Produk')
                    ->url(fn ($record) => ProductResource::getUrl('index', [
                        'tableFilters[category][value]' => $record->id
                    ]))
            ]);
    }
}