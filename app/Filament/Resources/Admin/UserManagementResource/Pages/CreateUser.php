<?php

namespace App\Filament\Resources\Admin\UserManagementResource\Pages;

use App\Filament\Resources\Admin\UserManagementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserManagementResource::class;
    
    protected function afterCreate(): void
    {
        // Call the afterCreate method on the resource
        UserManagementResource::afterCreate($this->record, $this->data);
    }
}