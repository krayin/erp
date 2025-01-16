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
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Resources\EmployeeResource;
use Webkul\Recruitment\Enums\ApplicationStatus;
use Webkul\Recruitment\Enums\RecruitmentState;
use Webkul\Recruitment\Mail\ApplicationConfirm;
use Webkul\Recruitment\Models\Applicant;
use Webkul\Recruitment\Models\RefuseReason;
use Webkul\Support\Services\EmailService;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Recruitment\Models\Stage;
use Webkul\Security\Models\User;

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
            ChatterActions\ChatterAction::make()
                ->setResource(static::$resource),
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

    // protected function mutateFormDataBeforeSave(array $data): array
    // {
    //     if ($data['job_id']) {
    //         $data['stage_id']    = Stage::where('is_default', 1)->first()->id ?? null;

    //         $this->notifyCandidate($data);
    //     } else {
    //         $data['stage_id'] = null;
    //     }

    //     return $data;
    // }

    // private function notifyCandidate(array $data): void
    // {
    //     app(EmailService::class)->send(
    //         mailClass: ApplicationConfirm::class,
    //         view: $viewName = 'recruitments::mails.application-confirm',
    //         payload: $data = $this->preparePayload($data),
    //     );

    //     $data['from']['company'] = Auth::user()->defaultCompany->toArray();

    //     $data['body'] = view($viewName, ['payload' => $data])->render();

    //     $data['type'] = 'comment';

    //     $this->record->addMessage($data, Auth::user()->id);
    // }

    // private function preparePayload(array $data): array
    // {
    //     return [
    //         'record_name'    => $this->record->candidate->name,
    //         'job_position'   => $jobPosition = $this->record->job?->name,
    //         'subject'        => __('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.mail.subject', [
    //             'job_position' => $jobPosition,
    //         ]),
    //         'to'             => [
    //             'address' => $this->record->candidate->email_from,
    //             'name'    => $this->record->candidate->name,
    //         ],
    //     ];
    // }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->record;
        $oldData = $record->toArray();

        if (isset($data['recruiter_id']) && $data['recruiter_id'] !== $oldData['recruiter_id']) {
            $data['date_opened'] = now();
        }

        if (isset($data['stage_id']) && $data['stage_id'] !== $oldData['stage_id']) {
            $data['date_last_stage_updated'] = now();
            $data['last_stage_id'] = $oldData['stage_id'];
        }

        if (isset($data['job_id']) && !$oldData['job_id']) {
            $data['stage_id'] = Stage::where('is_default', 1)->first()->id ?? null;

            $this->afterSave(function () use ($data) {
                $this->notifyCandidate($data);
            });
        }

        if (isset($data['interviewer']) && is_array($data['interviewer'])) {
            $oldInterviewers = collect($record->interviewer->pluck('id'));
            $newInterviewers = collect($data['interviewer']);

            $this->afterSave(function () use ($oldInterviewers, $newInterviewers) {
                $this->handleInterviewerChanges($oldInterviewers, $newInterviewers);
            });
        }

        if (isset($data['company_id'])) {
            $this->afterSave(function () use ($data) {
                $this->propagateCompanyChange($data['company_id']);
            });
        }

        return $data;
    }

    protected function handleInterviewerChanges(Collection $oldInterviewers, Collection $newInterviewers): void
    {

        // $removedInterviewers = $oldInterviewers->diff($newInterviewers);
        // $addedInterviewers = $newInterviewers->diff($oldInterviewers)->forget(Auth::id());

        // foreach ($addedInterviewers as $interviewerId) {
        //     $interviewer = User::find($interviewerId);

        //     Mail::to($interviewer->email)->send(new InterviewerAssigned([
        //         'applicant_name' => $this->record->candidate->name,
        //         'interviewer_name' => $interviewer->name,
        //         'job_position' => $this->record->job?->name,
        //     ]));
        // }

        // // Log the changes using HasLogActivity trait
        // if ($removedInterviewers->isNotEmpty() || $addedInterviewers->isNotEmpty()) {
        //     $this->record->logActivity(
        //         'interviewers_updated',
        //         'Interviewers list updated',
        //         [
        //             'removed' => $removedInterviewers->toArray(),
        //             'added' => $addedInterviewers->toArray(),
        //         ]
        //     );
        // }
    }

    protected function propagateCompanyChange(int $companyId): void
    {
        $this->record->candidate()->update([
            'company_id' => $companyId
        ]);
    }

    private function notifyCandidate(array $data): void
    {
        app(EmailService::class)->send(
            mailClass: ApplicationConfirm::class,
            view: $viewName = 'recruitments::mails.application-confirm',
            payload: $data = $this->preparePayload($data),
        );

        // Log the notification using HasChatter trait
        $this->record->addMessage([
            'from' => [
                'company' => Auth::user()->defaultCompany->toArray()
            ],
            'body' => view($viewName, ['payload' => $data])->render(),
            'type' => 'comment'
        ], Auth::user()->id);
    }

    private function preparePayload(array $data): array
    {
        return [
            'record_name' => $this->record->candidate->name,
            'job_position' => $jobPosition = $this->record->job?->name,
            'subject' => __('recruitments::filament/clusters/applications/resources/applicant/pages/edit-applicant.mail.subject', [
                'job_position' => $jobPosition,
            ]),
            'to' => [
                'address' => $this->record->candidate->email_from,
                'name' => $this->record->candidate->name,
            ],
        ];
    }
}
