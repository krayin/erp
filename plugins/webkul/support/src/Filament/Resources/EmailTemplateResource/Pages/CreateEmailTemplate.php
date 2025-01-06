<?php

namespace Webkul\Support\Filament\Resources\EmailTemplateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Webkul\Support\Filament\Resources\EmailTemplateResource;

class CreateEmailTemplate extends CreateRecord
{
    protected static string $resource = EmailTemplateResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            ...$data,
            'code' => $data['code'] ?? Str::snake($data['name']),
        ];
    }
}
