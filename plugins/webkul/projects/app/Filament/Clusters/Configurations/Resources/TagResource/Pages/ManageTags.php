<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\TagResource\Pages;

use Webkul\Project\Filament\Clusters\Configurations\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Components\Tab;
use Webkul\Project\Models\Tag;

class ManageTags extends ManageRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Tags')
                ->badge(Tag::count()),
            'archived' => Tab::make('Archived')
                ->badge(Tag::onlyTrashed()->count())
                ->modifyQueryUsing(function ($query) {
                    return $query->onlyTrashed();
                }),
        ];
    }
}
