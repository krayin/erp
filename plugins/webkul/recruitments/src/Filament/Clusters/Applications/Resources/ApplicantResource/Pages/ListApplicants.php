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
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Concerns\HasTableViews;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Recruitment\Enums\RecruitmentState;

class ListApplicants extends ListRecords
{
    use HasTableViews;

    protected static string $resource = ApplicantResource::class;

    public function getPresetTableViews(): array
    {
        return [
            'my_applications' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.my-applicants'))
                ->icon('heroicon-s-user-circle')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->where('recruiter_id', Auth::id());
                }),

            'un_assigned' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.un-assigned'))
                ->icon('heroicon-s-user-minus')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->whereNull('recruiter_id');
                }),

            'in_progress' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.in-progress'))
                ->icon('heroicon-s-arrow-path')
                ->favorite()
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->whereNull('deleted_at')
                        ->where('is_active', true)
                        ->whereNull('refuse_reason_id')
                        ->whereNull('date_closed');
                }),

            'hired' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.hired'))
                ->icon('heroicon-s-check-badge')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->whereNull('deleted_at')
                        ->where('is_active', true)
                        ->whereNotNull('date_closed');
                }),

            'refused' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.refused'))
                ->icon('heroicon-s-no-symbol')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->whereNull('deleted_at')
                        ->where('is_active', true)
                        ->whereNotNull('refuse_reason_id');
                }),

            'archived' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.archived'))
                ->icon('heroicon-s-archive-box')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->where(function (Builder $subQuery) {
                            $subQuery
                                ->whereNotNull('deleted_at')
                                ->orWhere('is_active', false);
                        });
                }),

            'blocked' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.blocked'))
                ->icon('heroicon-s-shield-exclamation')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->where(function (Builder $subQuery) {
                            $subQuery
                                ->where('is_active', false)
                                ->orWhere('state', RecruitmentState::BLOCKED->value);
                        });
                }),

            'directly_available' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.directly-available'))
                ->icon('heroicon-s-clock')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query
                        ->join('recruitments_candidates as candidates', 'recruitments_applicants.candidate_id', '=', 'candidates.id')
                        ->where(function (Builder $subQuery) {
                            $subQuery
                                ->where('candidates.availability_date', '<=', now()->format('Y-m-d'))
                                ->orWhere('candidates.availability_date', false);
                        });
                }),

            'created_recently' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.created-recently'))
                ->icon('heroicon-s-sparkles')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('create_date', '>=', now()->subDays(30)->toDateString());
                }),

            'stage_updated_recently' => PresetView::make(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.tabs.stage-updated-recently'))
                ->icon('heroicon-s-arrows-pointing-out')
                ->modifyQueryUsing(function (Builder $query) {
                    return $query->where('date_last_stage_updated', '>=', now()->subDays(30));
                }),
        ];
    }

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
                        ->title(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.notification.title'))
                        ->body(__('recruitments::filament/clusters/applications/resources/applicant/pages/list-applicant.notification.body')),
                ),
        ];
    }
}
