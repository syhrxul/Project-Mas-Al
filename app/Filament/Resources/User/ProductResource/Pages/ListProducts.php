<?php

namespace App\Filament\Resources\User\ProductResource\Pages;

use App\Filament\Resources\User\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions for users
        ];
    }
}