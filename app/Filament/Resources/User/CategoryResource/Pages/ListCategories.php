<?php

namespace App\Filament\Resources\User\CategoryResource\Pages;

use App\Filament\Resources\User\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions for users
        ];
    }
}