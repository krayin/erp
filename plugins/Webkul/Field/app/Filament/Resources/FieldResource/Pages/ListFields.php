<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Webkul\Field\Filament\Resources\FieldResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFields extends ListRecords
{
    protected static string $resource = FieldResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
