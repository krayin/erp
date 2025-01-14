<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource;
use Webkul\Recruitment\Models\Stage;

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
                ->mutateFormDataUsing(function (array $data): array {
                    $data['creator_id']  = Auth::id();
                    $data['company_id']  = Auth::user()->default_company_id;
                    $data['create_date'] = now();
                    $data['is_active']   = true;
                    $data['stage_id']    = Stage::where('is_default', 1)->first()->id ?? null;

                    return $data;
                })
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
