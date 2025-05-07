<?php

namespace App\Filament\Resources\User;

use App\Filament\Resources\User\RentalResource\Pages;
use App\Filament\Resources\User\RentalHistoryResource;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'My Cart';

    protected static ?string $modelLabel = 'Cart Item';

    protected static ?string $pluralModelLabel = 'My Cart';

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
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('remove')
                    ->label('Remove')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->visible(fn (Rental $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Rental $record) {
                        // Delete the rental without affecting product quantity
                        $record->delete();
                        
                        // Show notification
                        Notification::make()
                            ->title('Item removed')
                            ->body('The item has been removed from your cart.')
                            ->success()
                            ->send();
                    }),
            ])
            // In the bulkActions section, update the checkout action:
            ->bulkActions([
                Tables\Actions\BulkAction::make('checkout')
                    ->label('Checkout Selected Items')
                    ->icon('heroicon-o-shopping-bag')
                    ->color('success')
                    ->form([
                        TextInput::make('whatsapp_number')
                            ->label('Nomor WhatsApp')
                            ->tel()
                            ->required()
                            ->placeholder('Contoh: 08123456789')
                            ->regex('/^08[0-9]{8,11}$/')
                            ->helperText('Masukkan nomor WhatsApp aktif yang dapat dihubungi')
                            ->validationMessages([
                                'regex' => 'Format nomor WhatsApp tidak valid. Gunakan format 08xxxxxxxxxx',
                            ])
                            ->dehydrateStateUsing(function ($state) {
                                // Hapus semua karakter non-digit
                                $number = preg_replace('/[^0-9]/', '', $state);
                                
                                // Jika dimulai dengan 0, ganti dengan 62
                                if (str_starts_with($number, '0')) {
                                    $number = '62' . substr($number, 1);
                                }
                                
                                return $number;
                            }),
                    ])
                    ->action(function (Collection $records, array $data) {
                        foreach ($records as $record) {
                            if ($record->status === 'pending') {
                                $product = $record->product;
                                
                                $product->update([
                                    'quantity' => $product->quantity - $record->quantity
                                ]);
                                
                                $record->update([
                                    'status' => 'waiting',
                                    'whatsapp_number' => $data['whatsapp_number']
                                ]);
                                
                                Notification::make()
                                    ->title('Order submitted')
                                    ->body("Pesanan Anda untuk {$record->product->title} telah dikirim dan menunggu konfirmasi admin. Admin akan menghubungi Anda melalui WhatsApp untuk informasi pembayaran.")
                                    ->success()
                                    ->send();
                            }
                        }
                        
                        return redirect(RentalHistoryResource::getUrl('index'));
                    })
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        // Only show pending items in the cart
        return parent::getEloquentQuery()
            ->where('user_id', Auth::id())
            ->where('status', 'pending');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentals::route('/'),
            'view' => Pages\ViewRental::route('/{record}'),
        ];
    }
}
