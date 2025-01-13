<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

use Webkul\Recruitment\Filament\Clusters\Applications;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\RelationManagers;
use Webkul\Recruitment\Models\Applicant;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Webkul\Security\Filament\Resources\UserResource;
use Webkul\Field\Filament\Forms\Components\ProgressStepper;
use Webkul\Recruitment\Models\Stage as RecruitmentStage;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $cluster = Applications::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        if (str_contains(Route::currentRouteName(), 'index')) {
            return SubNavigationPosition::Start;
        }

        return SubNavigationPosition::Top;
    }

    public static function getModelLabel(): string
    {
        return __('Applicants');
    }

    public static function getNavigationGroup(): string
    {
        return __('Recruitment');
    }

    public static function getNavigationLabel(): string
    {
        return __('Applicants');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'email_from',
            'phone',
            'company.name',
            'degree.name',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        ProgressStepper::make('stage_id')
                            ->hiddenLabel()
                            ->inline()
                            ->required()
                            ->options(fn() => RecruitmentStage::orderBy('sort')->get()->mapWithKeys(fn($stage) => [$stage->id => $stage->name]))
                            ->default(RecruitmentStage::first()?->id)
                            ->columnSpan('full')
                            ->live()
                            ->afterStateUpdated(function ($state, $record) {
                                if ($record && $state) {
                                    $selectedStage = RecruitmentStage::find($state);

                                    $data = [
                                        'stage_id'               => $state,
                                        'last_stage_id'          => $record->stage_id,
                                        'date_last_stage_update' => now(),
                                    ];

                                    if ($selectedStage && $selectedStage->hired_stage) {
                                        $data['date_closed'] = now();
                                    } elseif ($record->stage && $record->stage->hired_stage) {
                                        $data['date_closed'] = null;
                                    }

                                    $record->update($data);
                                }
                            })
                    ])
                    ->columns(1),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('General Information')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Actions::make([
                                            Forms\Components\Actions\Action::make('good')
                                                ->hiddenLabel()
                                                ->outlined(false)
                                                ->icon(fn($record) => $record?->priority >= 1 ? 'heroicon-s-star' : 'heroicon-o-star')
                                                ->color('warning')
                                                ->size(ActionSize::ExtraLarge)
                                                ->iconButton()
                                                ->tooltip('Evaluation: Good')
                                                ->action(function ($record) {
                                                    if ($record?->priority == 1) {
                                                        $record->update(['priority' => 0]);
                                                        $record->candidate->update(['priority' => 0]);
                                                    } else {
                                                        $record->update(['priority' => 1]);
                                                        $record->candidate->update(['priority' => 1]);
                                                    }
                                                }),
                                            Forms\Components\Actions\Action::make('veryGood')
                                                ->hiddenLabel()
                                                ->icon(fn($record) => $record?->priority >= 2 ? 'heroicon-s-star' : 'heroicon-o-star')
                                                ->color('warning')
                                                ->size(ActionSize::ExtraLarge)
                                                ->iconButton()
                                                ->tooltip('Evaluation: Very Good')
                                                ->action(function ($record) {
                                                    if ($record?->priority == 2) {
                                                        $record->update(['priority' => 0]);
                                                        $record->candidate->update(['priority' => 0]);
                                                    } else {
                                                        $record->update(['priority' => 2]);
                                                        $record->candidate->update(['priority' => 2]);
                                                    }
                                                }),
                                            Forms\Components\Actions\Action::make('excellent')
                                                ->hiddenLabel()
                                                ->icon(fn($record) => $record?->priority >= 3 ? 'heroicon-s-star' : 'heroicon-o-star')
                                                ->color('warning')
                                                ->size(ActionSize::ExtraLarge)
                                                ->iconButton()
                                                ->tooltip('Evaluation: Excellent')
                                                ->action(function ($record) {
                                                    if ($record?->priority == 3) {
                                                        $record->update(['priority' => 0]);
                                                        $record->candidate->update(['priority' => 0]);
                                                    } else {
                                                        $record->update(['priority' => 3]);
                                                        $record->candidate->update(['priority' => 3]);
                                                    }
                                                }),
                                        ]),
                                        Forms\Components\Placeholder::make('date_closed')
                                            ->hidden(fn($record) => !$record->date_closed)
                                            ->live()
                                            ->hiddenLabel()
                                            ->content(function ($record) {
                                                $html = '<span style="display: inline-flex; align-items: center; background-color: #28a745; color: white; padding: 4px 8px; border-radius: 12px; font-size: 18px; font-weight: 500;">';

                                                $html .= view('filament::components.icon', [
                                                    'icon' => 'heroicon-c-check-badge',
                                                    'class' => 'w-6 h-6',
                                                ])->render();

                                                $html .= 'HIRED';
                                                $html .= '</span>';

                                                return new HtmlString($html);
                                            }),
                                    ])
                                    ->extraAttributes([
                                        'class' => 'flex !items-center justify-between'
                                    ])
                                    ->columns(2),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('candidate_id')
                                            ->relationship('candidate', 'name')
                                            ->required()
                                            ->preload()
                                            ->searchable()
                                            ->live()
                                            ->afterStateHydrated(function (Set $set, Get $get, $state) {
                                                if ($state) {
                                                    $candidate = \Webkul\Recruitment\Models\Candidate::find($state);

                                                    $set('candidate.email_from', $candidate?->email_from);
                                                    $set('candidate.phone', $candidate?->phone);
                                                    $set('candidate.linkedin_profile', $candidate?->linkedin_profile);
                                                }
                                            })
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('candidate.email_from')
                                            ->label(__('Email'))
                                            ->email()
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('candidate.phone')
                                            ->label(__('Phone'))
                                            ->tel()
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\TextInput::make('candidate.linkedin_profile')
                                            ->label(__('LinkedIn Profile'))
                                            ->url()
                                            ->required()
                                            ->columnSpan(1),
                                        Forms\Components\Select::make('job_id')
                                            ->relationship('job', 'name')
                                            ->label(__('Job Position'))
                                            ->preload()
                                            ->searchable(),
                                        Forms\Components\Select::make('recruiter')
                                            ->relationship('recruiter', 'name')
                                            ->label(__('Recruiter'))
                                            ->preload()
                                            ->searchable(),
                                        Forms\Components\Select::make('recruitments_applicant_interviewers')
                                            ->relationship('interviewer', 'name')
                                            ->label(__('Interviewer'))
                                            ->preload()
                                            ->multiple()
                                            ->searchable()
                                            ->createOptionForm(fn(Form $form) => UserResource::form($form)),
                                        Forms\Components\Select::make('recruitments_applicant_applicant_categories')
                                            ->multiple()
                                            ->label(__('Tags'))
                                            ->afterStateHydrated(function (Select $component, $state, $record) {
                                                if (
                                                    empty($state)
                                                    && $record?->candidate
                                                ) {
                                                    $component->state($record->candidate->categories->pluck('id')->toArray());
                                                }
                                            })
                                            ->relationship('categories', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ])
                                    ->columns(2)
                            ]),
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\RichEditor::make('applicant_notes')
                                    ->label(__('Notes'))
                                    ->columnSpan(2),
                            ])
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Education & Availability')
                            ->relationship('candidate', 'name')
                            ->schema([
                                Forms\Components\Select::make('degree_id')
                                    ->relationship('degree', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DatePicker::make('availability_date')
                                    ->native(false),
                            ]),
                        Forms\Components\Section::make('Department')
                            ->schema([
                                Forms\Components\Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload(),
                            ]),
                        Forms\Components\Section::make('Salary')
                            ->schema([
                                Forms\Components\TextInput::make('salary_expected')
                                    ->label(__('Expected Salary'))
                                    ->numeric()
                                    ->step(0.01),
                                Forms\Components\TextInput::make('salary_proposed')
                                    ->label(__('Proposed Salary'))
                                    ->numeric()
                                    ->step(0.01),
                            ]),
                        Forms\Components\Section::make('Source & Medium')
                            ->schema([
                                Forms\Components\Select::make('source_id')
                                    ->relationship('source', 'name')
                                    ->label(__('Source')),
                                Forms\Components\Select::make('medium_id')
                                    ->relationship('medium', 'name')
                                    ->label(__('Medium')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('candidate.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('job.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('stage.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('create_date')
                    ->date()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Status'),
                TextColumn::make('priority')
                    ->label('Evaluation'),
                TextColumn::make('user.name')
                    ->label('Assigned To')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewApplicant::class,
            Pages\EditApplicant::class,
            Pages\ManageSkill::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Manage Skills', [
                RelationManagers\SkillsRelationManager::class,
            ])
                ->icon('heroicon-o-bolt'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApplicants::route('/'),
            'view'   => Pages\ViewApplicant::route('/{record}'),
            'edit'   => Pages\EditApplicant::route('/{record}/edit'),
            'skills' => Pages\ManageSkill::route('/{record}/skills'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
