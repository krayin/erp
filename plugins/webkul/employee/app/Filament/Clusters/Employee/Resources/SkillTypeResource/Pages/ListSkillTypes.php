<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\Pages;

use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSkillTypes extends ListRecords
{
    protected static string $resource = SkillTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->after(function ($record, $livewire) {
                    return redirect(
                        SkillTypeResource::getUrl('edit', ['record' => $record])
                    );
                })
        ];
    }
}
