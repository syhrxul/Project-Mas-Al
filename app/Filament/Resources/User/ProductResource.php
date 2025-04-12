<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $modelLabel = 'Product';

    protected static ?string $pluralModelLabel = 'Products';

    protected static ?string $navigationGroup = 'Catalog';

    // In the table method, add the quantity column
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image')
                    ->square(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price_12_hours')
                    ->label('Price (12 Hours)')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('price_24_hours')
                    ->label('Price (24 Hours)')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Available Quantity')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    // In the infolist method, add the quantity field
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Product Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('image')
                            ->columnSpanFull(),
                        
                        Infolists\Components\TextEntry::make('title'),
                        
                        Infolists\Components\TextEntry::make('category.name')
                            ->label('Category'),
                        
                        Infolists\Components\TextEntry::make('price_12_hours')
                            ->label('Price (12 Hours)')
                            ->money('IDR'),
                        
                        Infolists\Components\TextEntry::make('price_24_hours')
                            ->label('Price (24 Hours)')
                            ->money('IDR'),
                        
                        Infolists\Components\TextEntry::make('quantity')
                            ->label('Available Quantity'),
                        
                        Infolists\Components\TextEntry::make('description')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'view' => Pages\ViewProduct::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('is_active', true);
    }
}