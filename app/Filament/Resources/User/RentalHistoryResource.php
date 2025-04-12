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
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('end_datetime')
                    ->label('End Date')
                    ->date()
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
                        'warning' => 'waiting',
                        'success' => 'confirmed',
                        'danger' => ['cancelled', 'rejected'],
                        'primary' => 'completed',
                    ]),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable(),
                
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'waiting' => 'Waiting Approval',
                        'confirmed' => 'Confirmed',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
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
            ->whereIn('status', ['waiting', 'confirmed', 'completed', 'cancelled', 'rejected'])
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