<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewCandidate extends ViewRecord
{
    protected static string $resource = CandidateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/candidate/pages/view-candidate.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/candidate/pages/view-candidate.header-actions.delete.notification.body'))
                ),
        ];
    }
}
