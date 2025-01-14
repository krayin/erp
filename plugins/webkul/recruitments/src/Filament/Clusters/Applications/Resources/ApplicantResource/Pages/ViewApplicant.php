<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Recruitment\Enums\ApplicationStatus;
use Webkul\Recruitment\Models\Applicant;
use Webkul\Recruitment\Models\RefuseReason;


class ViewApplicant extends ViewRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.delete.notification.body'))
                ),
            Action::make('Refuse')
                ->modalIcon('heroicon-s-bug-ant')
                ->hidden(fn($record) => $record->refuse_reason_id || $record->application_status->value === ApplicationStatus::ARCHIVED->value)
                ->modalHeading('Refuse Reason')
                ->form(function (Form $form, $record) {
                    return $form->schema([
                        Forms\Components\ToggleButtons::make('refuse_reason_id')
                            ->hiddenLabel()
                            ->inline()
                            ->live()
                            ->options(RefuseReason::all()->pluck('name', 'id')),
                        Forms\Components\Toggle::make('notify')
                            ->inline()
                            ->live()
                            ->default(true)
                            ->visible(fn(Get $get) => $get('refuse_reason_id'))
                            ->label('Notify'),
                        Forms\Components\TextInput::make('email')
                            ->visible(fn(Get $get) => $get('notify') && $get('refuse_reason_id'))
                            ->default($record->candidate->email_from)
                            ->label('Email To')
                    ]);
                })
                ->action(function (array $data, Applicant $record) {
                    $record->setAsRefused($data['refuse_reason_id']);

                    Notification::make()
                        ->info()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.refuse.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.refuse.notification.body'))
                        ->send();
                }),
            Action::make('Restore')
                ->hidden(fn($record) => ! $record->refuse_reason_id)
                ->modalHeading('Restore Applicant from refuse')
                ->requiresConfirmation()
                ->color('gray')
                ->action(function (Applicant $record) {
                    $record->reopen();

                    Notification::make()
                        ->info()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.reopen.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/view-applicant.header-actions.reopen.notification.body'))
                        ->send();
                })
        ];
    }
}
