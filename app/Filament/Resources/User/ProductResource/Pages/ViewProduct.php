<?php

namespace App\Filament\Resources\User\ProductResource\Pages;

use App\Filament\Resources\User\ProductResource;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('rent')
                ->label('Rent This Product')
                ->color('primary')
                ->icon('heroicon-o-shopping-cart')
                ->form([
                    DateTimePicker::make('start_datetime')
                        ->label('Start Date and Time')
                        ->required()
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->native(false)
                        ->minDate(now())
                        ->live()
                        ->reactive(),

                    DateTimePicker::make('end_datetime')
                        ->label('End Date and Time')
                        ->required()
                        ->seconds(false)
                        ->displayFormat('d/m/Y H:i')
                        ->native(false)
                        ->minDate(fn (Get $get) => $get('start_datetime'))
                        ->live()
                        ->reactive(),

                    TextInput::make('quantity')
                        ->label('Quantity to Rent')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(fn () => $this->record->quantity)
                        ->default(1)
                        ->reactive(),

                    Textarea::make('notes')
                        ->label('Notes for Admin (Optional)')
                        ->placeholder('Any special requests or notes for this rental?')
                        ->maxLength(500)
                        ->reactive(),
                ])
                ->action(function (array $data): void {
                    $product = $this->record;
                    $startDateTime = Carbon::parse($data['start_datetime']);
                    $endDateTime = Carbon::parse($data['end_datetime']);
                    $quantity = (int) $data['quantity'];

                    // Add validation for maximum quantity
                    if ($quantity > 100) {
                        Notification::make()
                            ->title('Invalid quantity')
                            ->body('Maksimum pemesanan adalah 100 unit.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Calculate the total price
                    $totalPrice = $this->calculateTotalPrice($startDateTime, $endDateTime, $quantity);

                    // Add validation for maximum total price
                    if ($totalPrice > 999999999999.99) {
                        Notification::make()
                            ->title('Total harga terlalu besar')
                            ->body('Total harga sewa melebihi batas maksimum yang diizinkan.')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Create the rental without reducing product quantity
                    Rental::create([
                        'user_id' => Auth::id(),
                        'product_id' => $product->id,
                        'start_datetime' => $startDateTime,
                        'end_datetime' => $endDateTime,
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                        'status' => 'pending',
                        'notes' => $data['notes'] ?? null,
                    ]);

                    // Remove the product quantity update - we'll do this at checkout instead

                    Notification::make()
                        ->title('Item added to cart')
                        ->body('Your item has been added to your cart.')
                        ->success()
                        ->send();
                })
                ->visible(fn () => $this->record->quantity > 0),
        ];
    }

private function calculateTotalPrice($startDateTime, $endDateTime, $quantity = 1)
{
    if (!$startDateTime || !$endDateTime) {
        return 0;
    }

    $product = $this->record;
    $start = Carbon::parse($startDateTime);
    $end = Carbon::parse($endDateTime);

    if ($end->lessThanOrEqualTo($start)) {
        throw new \Exception('Invalid duration selected. End date must be after start date.');
    }

    $durationInHours = $start->diffInHours($end);

    if ($durationInHours <= 12) {
        $total = $product->price_12_hours;
    } elseif ($durationInHours <= 24) {
        $total = $product->price_24_hours;
    } else {
        $days = floor($durationInHours / 24); // Hanya full days yang dihitung
        $total = $days * $product->price_24_hours;
    }

    return $total * $quantity;
}

}
