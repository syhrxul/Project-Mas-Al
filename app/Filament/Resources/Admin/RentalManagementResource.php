<?php

namespace App\Filament\Resources\Admin;

use App\Filament\Resources\Admin\RentalManagementResource\Pages;
use App\Models\Rental;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use App\Services\InvoiceService;

class RentalManagementResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Rental Management';

    protected static ?string $modelLabel = 'Rental Request';

    protected static ?string $pluralModelLabel = 'Rental Requests';

    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('admin_notes')
                    ->label('Rejection Reason (Optional)')
                    ->placeholder('Explain why this rental is being rejected')
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\ImageColumn::make('product.image')
                    ->label('Product')
                    ->disk('public')
                    ->square(),
                
                Tables\Columns\TextColumn::make('product.title')
                    ->label('Product Name')
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
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Requested On')
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
                
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Rental $record) => $record->status === 'waiting')
                    ->requiresConfirmation()
                    ->action(function (Rental $record) {
                        // Update status to confirmed
                        $record->update([
                            'status' => 'confirmed',
                        ]);
                        
                        // Show notification
                        Notification::make()
                            ->title('Rental approved')
                            ->body("The rental request has been approved.")
                            ->success()
                            ->send();
                        
                        // Generate dan kirim invoice secara otomatis
                        $invoiceService = new InvoiceService();
                        return $invoiceService->generateAndSendInvoice($record);
                    }),
                
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Rental $record) => $record->status === 'waiting')
                    ->form([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Rejection Reason')
                            ->placeholder('Explain why this rental is being rejected')
                            ->required()
                            ->maxLength(500),
                    ])
                    ->action(function (Rental $record, array $data) {
                        // Get the product
                        $product = $record->product;
                        
                        // Restore the product quantity
                        $product->update([
                            'quantity' => $product->quantity + $record->quantity
                        ]);
                        
                        // Update rental status and add admin notes
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['admin_notes'],
                        ]);
                        
                        // Show notification
                        Notification::make()
                            ->title('Rental rejected')
                            ->body("The rental request has been rejected with explanation.")
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentalManagements::route('/'),
            'view' => Pages\ViewRentalManagement::route('/{record}'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\StatsOverviewWidget::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'waiting')->count();
    }

    public function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('ID')
                ->sortable(),
            
            Tables\Columns\TextColumn::make('user.name')
                ->label('Pelanggan')
                ->searchable()
                ->sortable(),
                
            Tables\Columns\TextColumn::make('product.title')
                ->label('Produk')
                ->searchable()
                ->sortable(),
                
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'waiting' => 'warning',
                    'confirmed' => 'success',
                    'cancelled' => 'danger',
                    'rejected' => 'danger',
                    'completed' => 'primary',
                    default => 'gray',
                })
                ->sortable(),
                
            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal Permintaan')
                ->dateTime()
                ->sortable(),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'waiting' => 'Menunggu',
                    'confirmed' => 'Aktif',
                    'cancelled' => 'Dibatalkan',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai',
                ])
                ->multiple()
                ->default(['waiting', 'confirmed']),
        ];
    }
}