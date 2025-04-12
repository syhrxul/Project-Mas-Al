<?php

namespace App\Filament\Resources\User\RentalResource\Pages;

use App\Filament\Resources\User\RentalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRental extends EditRecord
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
