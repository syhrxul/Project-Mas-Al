<?php

namespace App\Filament\Resources\Admin\UserManagementResource\Pages;

use App\Filament\Resources\Admin\UserManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}