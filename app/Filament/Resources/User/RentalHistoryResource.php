<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\RentalHistoryResource\Pages;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RentalHistoryResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Rental History';

    protected static ?string $modelLabel = 'Rental History';

    protected static ?string $pluralModelLabel = 'Rental History';

    protected static ?string $navigationGroup = 'Account';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product.image')
                    ->label('Image')
                    ->disk('public')
                    ->square(),
                
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('start_datetime')
                    ->label('Start Date & Time')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('end_datetime')
                    ->label('End Date & Time')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->getStateUsing(function ($record) {
                        $start = Carbon::parse($record->start_datetime);
                        $end = Carbon::parse($record->end_datetime);
                        $diffInHours = $start->diffInHours($end);
                        
                        if ($diffInHours < 24) {
                            return $diffInHours . ' jam';
                        }
                        
                        $days = floor($diffInHours / 24);
                        $hours = $diffInHours % 24;
                        
                        return $days . ' hari ' . ($hours > 0 ? $hours . ' jam' : '');
                    }),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'confirmed',
                        'primary' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Confirmed On')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'confirmed' => 'Confirmed',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->whereIn('status', ['confirmed', 'completed', 'cancelled'])
            ->orderBy('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalHistories::route('/'),
            'view' => Pages\ViewRentalHistory::route('/{record}'),
        ];
    }
}