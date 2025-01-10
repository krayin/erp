<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

use Webkul\Recruitment\Filament\Clusters\Applications;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;
use Webkul\Recruitment\Models\Applicant;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Forms\Components\Section;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\ActionSize;
use Illuminate\Support\HtmlString;

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
                        Forms\Components\Section::make('Basic Information')
                            ->schema([
                                Forms\Components\Actions::make([
                                    Forms\Components\Actions\Action::make('good')
                                        ->hiddenLabel()
                                        ->outlined(false)
                                        ->icon(fn ($record) => $record?->priority >= 1 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color(fn ($record) => $record?->priority >= 1 ? 'warning' : 'gray')
                                        ->size(ActionSize::ExtraLarge)
                                        ->iconButton()
                                        ->tooltip('Evaluation: Good')
                                        ->action(fn ($record) => $record?->update(['priority' => 1])),
                                    Forms\Components\Actions\Action::make('veryGood')
                                        ->hiddenLabel()
                                        ->icon(fn ($record) => $record?->priority >= 2 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color(fn ($record) => $record?->priority >= 2 ? 'warning' : 'gray')
                                        ->size(ActionSize::ExtraLarge)
                                        ->iconButton()
                                        ->tooltip('Evaluation: Very Good')
                                        ->action(fn ($record) => $record?->update(['priority' => 2])),
                                    Forms\Components\Actions\Action::make('excellent')
                                        ->hiddenLabel()
                                        ->icon(fn ($record) => $record?->priority >= 3 ? 'heroicon-s-star' : 'heroicon-o-star')
                                        ->color(fn ($record) => $record?->priority >= 3 ? 'warning' : 'gray')
                                        ->size(ActionSize::ExtraLarge)
                                        ->iconButton()
                                        ->tooltip('Evaluation: Excellent')
                                        ->action(fn ($record) => $record?->update(['priority' => 3]))
                                ]),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('candidate_id')
                                            ->relationship('candidate', 'partner_name')
                                            ->required()
                                            ->preload()
                                            ->searchable()
                                            ->live()
                                            ->afterStateUpdated(function (Set $set, $state) {
                                                if ($state) {
                                                    $candidate = \Webkul\Recruitment\Models\Candidate::find($state);

                                                    $set('candidate.email_normalized', $candidate?->email_normalized);
                                                    $set('candidate.phone_sanitized', $candidate?->phone_sanitized);
                                                    $set('candidate.linkedin_profile', $candidate?->linkedin_profile);
                                                }
                                            })
                                    ])
                                    ->columns(2),
                                Forms\Components\Group::make()
                                    ->relationship('candidate', 'partner_name')
                                    ->schema([
                                        Forms\Components\TextInput::make('email_normalized')
                                            ->email()
                                            ->required(),
                                        Forms\Components\TextInput::make('phone_sanitized')
                                            ->tel()
                                            ->required(),
                                        Forms\Components\TextInput::make('linkedin_profile')
                                            ->url()
                                            ->required(),
                                    ])->columns(2),
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\Select::make('job_id')
                                            ->relationship('job', 'name')
                                            ->searchable(),
                                        Forms\Components\Select::make('department_id')
                                            ->relationship('department', 'name')
                                            ->searchable(),
                                        Forms\Components\Select::make('company_id')
                                            ->relationship('company', 'name')
                                            ->searchable(),
                                        Forms\Components\Select::make('stage_id')
                                            ->relationship('stage', 'name')
                                            ->required()
                                            ->searchable(),
                                        Forms\Components\TextInput::make('email_cc')
                                            ->email()
                                            ->label('Email CC'),
                                    ])->columns(2)
                            ]),
                        Forms\Components\Section::make('Source Information')
                            ->schema([
                            Forms\Components\Select::make('source_id')
                                    ->relationship('source', 'name')
                                    ->preload()
                                    ->searchable(),
                            Forms\Components\Select::make('medium_id')
                                    ->relationship('medium', 'name')
                                    ->preload()
                                    ->searchable(),
                            Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->preload()
                                    ->label('Assigned To')
                                    ->searchable(),
                            ])->columns(2),
                        Forms\Components\Section::make('Salary Information')
                            ->schema([
                                Forms\Components\TextInput::make('salary_expected')
                                    ->numeric()
                                    ->label('Expected Salary'),
                                Forms\Components\TextInput::make('salary_expected_extra')
                                    ->label('Expected Salary Extra'),
                                Forms\Components\TextInput::make('salary_proposed')
                                    ->numeric()
                                    ->label('Proposed Salary'),
                                Forms\Components\TextInput::make('salary_proposed_extra')
                                    ->label('Proposed Salary Extra'),
                            ])->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\Section::make('Status')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active Status'),
                                Forms\Components\TextInput::make('priority')
                                    ->numeric()
                                    ->label('Evaluation'),
                                Forms\Components\TextInput::make('probability')
                                    ->numeric()
                                    ->label('Probability'),
                            ]),

                        Forms\Components\Section::make('Dates')
                            ->schema([
                                Forms\Components\DatePicker::make('create_date')
                                    ->label('Applied On'),
                                Forms\Components\DatePicker::make('date_opened')
                                    ->label('Assigned Date'),
                                Forms\Components\DatePicker::make('date_closed')
                                    ->label('Hired Date'),
                                Forms\Components\DatePicker::make('refuse_date')
                                    ->label('Refused Date'),
                            ]),

                        Forms\Components\Section::make('Additional Information')
                            ->schema([
                                Forms\Components\Select::make('refuse_reason_id')
                                    ->relationship('refuseReason', 'name')
                                    ->searchable(),
                                Forms\Components\Textarea::make('applicant_notes')
                                    ->label('Notes'),
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
