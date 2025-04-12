<?php

namespace App\Filament\Resources\User\RentalResource\Pages;

use App\Filament\Resources\User\RentalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRentals extends ListRecords
{
    protected static string $resource = RentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions for users
        ];
    }
}
