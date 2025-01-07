<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStages extends ListRecords
{
    protected static string $resource = StageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('recruitments::filament/clusters/configurations/resources/stage/pages/list-stage.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle')
                ->mutateFormDataUsing(function ($data) {
                    return $data;
                }),
        ];
    }
}
