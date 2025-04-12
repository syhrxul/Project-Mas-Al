<?php

namespace App\Filament\User\Resources;

use App\Filament\User\Resources\CartResource\Pages;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\Rent;
use Carbon\Carbon;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Rents';
    protected static ?int $navigationSort = 1;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('product.image')
                    ->disk('public')
                    ->square(),

                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_time')
                    ->label('Start Time')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_time')
                    ->label('End Time')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('rental_duration')
                    ->label('Duration')
                    ->getStateUsing(function (Cart $record): string {
                        $start = Carbon::parse($record->start_time);
                        $end = Carbon::parse($record->end_time);

                        if ($end->lessThanOrEqualTo($start)) {
                            return 'Invalid time range';
                        }

                        $diffInHours = $start->diffInHours($end);

                        if ($diffInHours < 24) {
                            return $diffInHours . ' jam';
                        }

                        $days = floor($diffInHours / 24);
                        $hours = $diffInHours % 24;

                        return $days . ' hari ' . ($hours > 0 ? $hours . ' jam' : '');
                    }),

                Tables\Columns\TextColumn::make('rental_price')
                    ->label('Price')
                    ->getStateUsing(function (Cart $record): string {
                        $start = Carbon::parse($record->start_time);
                        $end = Carbon::parse($record->end_time);

                        if ($end->lessThanOrEqualTo($start)) {
                            return 'Rp 0';
                        }

                        $diffInHours = $start->diffInHours($end);
                        $quantity = $record->quantity;

                        if ($diffInHours > 12) {
                            $days = ceil($diffInHours / 24);
                            $pricePerItem = $record->product->price_24_hours * $days;
                        } else {
                            $pricePerItem = $record->product->price_12_hours;
                        }

                        $total = $pricePerItem * $quantity;
                        return 'Rp ' . number_format($total, 0, ',', '.');
                    }),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->modalHeading('Delete cart item')
                    ->modalDescription('Are you sure you want to delete this item from your cart?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('rent')
                    ->label('Sewa Sekarang')
                    ->icon('heroicon-o-shopping-cart')
                    ->action(function (Collection $records) {
                        $totalPrice = 0;

                        foreach ($records as $cart) {
                            $start = Carbon::parse($cart->start_time);
                            $end = Carbon::parse($cart->end_time);

                            if ($end->lessThan($start)) {
                                continue;
                            }

                            $diffInHours = $start->diffInHours($end);
                            $quantity = $cart->quantity;

                            if ($diffInHours > 12) {
                                $days = ceil($diffInHours / 24);
                                $pricePerItem = $cart->product->price_24_hours * $days;
                            } else {
                                $pricePerItem = $cart->product->price_12_hours;
                            }

                            $itemTotal = $pricePerItem * $quantity;
                            $totalPrice += $itemTotal;

                            // Create a rental transaction
                            Transaction::create([
                                'user_id' => $cart->user_id,
                                'product_id' => $cart->product_id,
                                'start_time' => $cart->start_time,
                                'end_time' => $cart->end_time,
                                'quantity' => $quantity,
                                'total_price' => $itemTotal,
                                'status' => 'pending',
                            ]);

                            // Update product quantity
                            $product = $cart->product;
                            $product->update([
                                'quantity' => $product->quantity - $quantity,
                            ]);

                            // Remove from cart
                            $cart->delete();
                        }

                        Notification::make()
                            ->title('Berhasil disewa!')
                            ->body('Total harga: Rp ' . number_format($totalPrice, 0, ',', '.'))
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarts::route('/'),
        ];
    }
}