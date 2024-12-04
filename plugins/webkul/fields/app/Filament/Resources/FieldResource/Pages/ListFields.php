<?php

namespace Webkul\Fields\Filament\Resources\FieldResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Webkul\Fields\Filament\Resources\FieldResource;
use Webkul\Fields\Models\Field;

class ListFields extends ListRecords
{
    protected static string $resource = FieldResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(__('field::app.resources.pages.list-records.index.tabs.all'))
                ->badge(Field::count()),
            'archived' => Tab::make(__('field::app.resources.pages.list-records.index.tabs.archived'))
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
