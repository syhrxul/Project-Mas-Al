<?php

namespace App\Filament\Resources\User\CategoryResource\Pages;

use App\Filament\Resources\User\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCategory extends ViewRecord
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions for users
        ];
    }
}