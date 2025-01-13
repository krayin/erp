<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource;

class ListApplicants extends ListRecords
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalHeading('Create Applicant')
                ->modalIcon('heroicon-s-user')
                ->form([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Select::make('candidate_id')
                                ->relationship('candidate', 'name')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->label('Candidate')
                                ->createOptionForm(fn(Form $form) => CandidateResource::form($form)),
                        ])->columns(2),
                ])
                ->createAnother(false)
                ->after(function ($record) {
                    return redirect(
                        static::$resource::getUrl('edit', ['record' => $record]),
                    );
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('Applicant created'))
                        ->body(__('Applicant create has been created successfully.')),
                ),
        ];
    }
}
