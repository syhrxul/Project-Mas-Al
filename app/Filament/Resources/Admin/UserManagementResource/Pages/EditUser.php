<?php

namespace App\Filament\Resources\Admin\UserManagementResource\Pages;

use App\Filament\Resources\Admin\UserManagementResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Log;

class EditUser extends EditRecord
{
    protected static string $resource = UserManagementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        // Log the data for debugging
        Log::info('EditUser afterSave', [
            'record_id' => $this->record->id,
            'data' => $this->data
        ]);
        
        // Call the afterUpdate method on the resource
        UserManagementResource::afterUpdate($this->record, $this->data);
    }
}