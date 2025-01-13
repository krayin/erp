<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

use Webkul\Recruitment\Filament\Clusters\Applications;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;
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
use Filament\Support\Enums\ActionSize;
use Webkul\Security\Filament\Resources\UserResource;

class ApplicantResource extends Resource
{
    protected static ?string $model = Applicant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Applications::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('General Information')
                            ->schema([
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('good')
                                        ->hiddenLabel()
                                        ->outlined(false)
                                        ->icon(fn($record) => $record?->priority >= 1 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color(fn($record) => $record?->priority >= 1 ? 'warning' : 'gray')
                                        ->size(ActionSize::ExtraLarge)
                                        ->iconButton()
                                        ->tooltip('Evaluation: Good')
                                        ->action(function ($record) {
                                            if ($record?->priority == 1) {
                                                $record->update(['priority' => 0]);
                                            } else {
                                                $record->update(['priority' => 1]);
                                            }
                                        }),
                                    Forms\Components\Actions\Action::make('veryGood')
                                        ->hiddenLabel()
                                        ->icon(fn($record) => $record?->priority >= 2 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color(fn($record) => $record?->priority >= 2 ? 'warning' : 'gray')
                                        ->size(ActionSize::ExtraLarge)
                                        ->iconButton()
                                        ->tooltip('Evaluation: Very Good')
                                        ->action(function ($record) {
                                            if ($record?->priority == 2) {
                                                $record->update(['priority' => 0]);
                                            } else {
                                                $record->update(['priority' => 2]);
                                            }
                                        }),
                                    Forms\Components\Actions\Action::make('excellent')
                                        ->hiddenLabel()
                                        ->icon(fn($record) => $record?->priority >= 3 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color(fn($record) => $record?->priority >= 3 ? 'warning' : 'gray')
                                        ->size(ActionSize::ExtraLarge)
                                        ->iconButton()
                                        ->tooltip('Evaluation: Excellent')
                                        ->action(function ($record) {
                                            if ($record?->priority == 3) {
                                                $record->update(['priority' => 0]);
                                            } else {
                                                $record->update(['priority' => 3]);
                                            }
                                        })
                                ]),
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListApplicants::route('/'),
            'view'   => Pages\ViewApplicant::route('/{record}'),
            'edit'   => Pages\EditApplicant::route('/{record}/edit'),
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
