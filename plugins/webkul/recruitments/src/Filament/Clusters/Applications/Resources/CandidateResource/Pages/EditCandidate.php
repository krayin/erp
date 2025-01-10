<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;

class EditCandidate extends EditRecord
{
    protected static string $resource = CandidateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('recruitments::filament/clusters/applications/resources/candidate/pages/edit-candidate.notification.title'))
            ->body(__('recruitments::filament/clusters/applications/resources/candidate/pages/edit-candidate.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()
                ->setResource(static::$resource),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/candidate/pages/edit-candidate.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/candidate/pages/edit-candidate.header-actions.delete.notification.body'))
                ),
        ];
    }
}
