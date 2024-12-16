<?php

namespace Webkul\Project\Filament\Resources\TaskResource\Pages;

use Webkul\Project\Filament\Resources\TaskResource;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();
    
        return $data;
    }
}
