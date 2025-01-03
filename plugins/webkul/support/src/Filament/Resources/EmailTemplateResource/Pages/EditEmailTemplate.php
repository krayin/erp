<?php

namespace Webkul\Support\Filament\Resources\EmailTemplateResource\Pages;

use Webkul\Support\Filament\Resources\EmailTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Str;

class EditEmailTemplate extends EditRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            ...$data,
            'code' => $data['code'] ?? Str::snake($data['name']),
        ];
    }
}
