<?php

namespace App\Filament\Resources\User\RentalResource\Pages;

use App\Filament\Resources\User\RentalResource;
use App\Models\Product;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Grid;
use Filament\Notifications\Notification;

class ViewRental extends ViewRecord
{
    protected static string $resource = RentalResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cancel')
                ->label('Cancel Rental')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'pending')
                ->action(function () {
                    // Get the rental details
                    $rental = $this->record;
                    
                    // Update the product quantity (add back the rented quantity)
                    $product = Product::find($rental->product_id);
                    $product->update([
                        'quantity' => $product->quantity + $rental->quantity
                    ]);
                    
                    // Update the rental status
                    $rental->update([
                        'status' => 'cancelled'
                    ]);
                    
                    // Show success notification
                    Notification::make()
                        ->title('Rental cancelled')
                        ->body('Your rental has been cancelled and the product quantity has been restored.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Rental Details')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('id')
                                    ->label('Rental ID')
                                    ->weight('bold'),
                                
                                TextEntry::make('created_at')
                                    ->label('Requested On')
                                    ->dateTime(),
                                
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'pending' => 'warning',
                                        'confirmed' => 'success',
                                        'cancelled' => 'danger',
                                        'completed' => 'primary',
                                        default => 'gray',
                                    }),
                            ]),
                    ]),
                
                Section::make('Product Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                ImageEntry::make('product.image')
                                    ->disk('public')
                                    ->height(200)
                                    ->columnSpanFull(),
                                
                                TextEntry::make('product.title')
                                    ->label('Product Name')
                                    ->weight('bold'),
                                
                                TextEntry::make('product.category.name')
                                    ->label('Category'),
                                
                                TextEntry::make('quantity')
                                    ->label('Quantity'),
                                
                                TextEntry::make('total_price')
                                    ->label('Total Price')
                                    ->money('IDR'),
                            ]),
                    ]),
                
                Section::make('Rental Period')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('start_datetime')
                                    ->label('Start Date & Time')
                                    ->dateTime(),
                                
                                TextEntry::make('end_datetime')
                                    ->label('End Date & Time')
                                    ->dateTime(),
                                
                                TextEntry::make('duration')
                                    ->label('Duration')
                                    ->state(function ($record) {
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
                            ]),
                    ]),
                
                Section::make('Additional Information')
                    ->schema([
                        TextEntry::make('notes')
                            ->label('Notes')
                            ->placeholder('No notes provided'),
                    ])
                    ->hidden(fn ($record) => empty($record->notes)),
            ]);
    }
}