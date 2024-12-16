<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\Pages;

use Webkul\Project\Filament\Resources\ProjectResource;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();
    
        return $data;
    }
}
