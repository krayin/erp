<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource;

class ListSkillTypes extends ListRecords
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->after(function ($record) {
                    return redirect(
                        SkillTypeResource::getUrl('edit', ['record' => $record])
                    );
                }),
        ];
    }
}
