<?php

namespace Webkul\Support\Filament\Resources\EmailTemplateResource\Pages;


use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Support\Filament\Resources\EmailTemplateResource;

class ViewEmailTemplate extends ViewRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
        ];
    }
}
