<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Employee\Filament\Resources\EmployeeResource;
use Webkul\Recruitment\Enums\ApplicationStatus;
use Webkul\Recruitment\Enums\RecruitmentState;
use Webkul\Recruitment\Models\Applicant;
use Webkul\Recruitment\Models\RefuseReason;

class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.notification.title'))
            ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('state')
                ->hiddenLabel()
                ->icon(function ($record) {
                    if ($record->state == RecruitmentState::DONE->value) {
                        return RecruitmentState::DONE->getIcon();
                    } else if ($record->state == RecruitmentState::BLOCKED->value) {
                        return RecruitmentState::BLOCKED->getIcon();
                    } else if ($record->state == RecruitmentState::NORMAL->value) {
                        return RecruitmentState::NORMAL->getIcon();
                    }
                })
                ->iconButton()
                ->color(function ($record) {
                    if ($record->state == RecruitmentState::DONE->value) {
                        return RecruitmentState::DONE->getColor();
                    } else if ($record->state == RecruitmentState::BLOCKED->value) {
                        return RecruitmentState::BLOCKED->getColor();
                    } else if ($record->state == RecruitmentState::NORMAL->value) {
                        return RecruitmentState::NORMAL->getColor();
                    }
                })
                ->form([
                    Forms\Components\ToggleButtons::make('state')
                        ->inline()
                        ->options(RecruitmentState::class)
                ])
                ->fillForm(fn($record) => [
                    'state' => $record->state
                ])
                ->tooltip(function ($record) {
                    if ($record->state == RecruitmentState::DONE->value) {
                        return RecruitmentState::DONE->getLabel();
                    } else if ($record->state == RecruitmentState::BLOCKED->value) {
                        return RecruitmentState::BLOCKED->getLabel();
                    } else if ($record->state == RecruitmentState::NORMAL->value) {
                        return RecruitmentState::NORMAL->getLabel();
                    }
                })
                ->action(function (Applicant $record, $data) {
                    $record->update($data);

                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.state.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.state.notification.body'))
                        ->send();
                }),
            Action::make('gotoEmployee')
                ->tooltip(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.goto-employee'))
                ->visible(fn($record) => $record->application_status->value == ApplicationStatus::HIRED->value || $record->candidate->employee_id)
                ->icon('heroicon-s-arrow-top-right-on-square')
                ->iconButton()
                ->action(function (Applicant $record) {
                    $employee = $record->createEmployee();

                    return redirect(EmployeeResource::getUrl('view', ['record' => $employee]));
                }),
            Action::make('createEmployee')
                ->label(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.create-employee'))
                ->hidden(fn($record) => $record->application_status->value == ApplicationStatus::HIRED->value || $record->candidate->employee_id)
                ->action(function (Applicant $record) {
                    $employee = $record->createEmployee();
                    return redirect(EmployeeResource::getUrl('edit', ['record' => $employee]));
                }),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.delete.notification.body'))
                ),
            Actions\ForceDeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.force-delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.force-delete.notification.body'))
                ),
            Actions\RestoreAction::make()
                ->successNotification(
                    Notification::make()
                        ->info()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.restore.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.restore.notification.body'))
                ),
            Action::make('refuse')
                ->modalIcon('heroicon-s-bug-ant')
                ->hidden(fn($record) => $record->refuse_reason_id || $record->application_status->value === ApplicationStatus::ARCHIVED->value)
                ->modalHeading(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.refuse.title'))
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
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.refuse.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.refuse.notification.body'))
                        ->send();
                }),
            Action::make('restore')
                ->hidden(fn($record) => ! $record->refuse_reason_id)
                ->modalHeading(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.reopen.title'))
                ->requiresConfirmation()
                ->color('gray')
                ->action(function (Applicant $record) {
                    $record->reopen();

                    Notification::make()
                        ->info()
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.reopen.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.header-actions.reopen.notification.body'))
                        ->send();
                }),
        ];
    }
}
