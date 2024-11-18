<?php

namespace Webkul\Field\Filament\Resources\FieldResource\Pages;

use Webkul\Field\Filament\Resources\FieldResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Webkul\Field\Models\Field;

class ListFields extends ListRecords
{
    protected static string $resource = FieldResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Fields')
                ->badge(Field::count()),
            'archived' => Tab::make('Archived')
                ->badge(Field::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus'),
        ];
    }
}
