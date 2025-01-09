<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Webkul\Recruitment\Filament\Clusters\Applications;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource\Pages;
use Webkul\Recruitment\Models\Candidate;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $cluster = Applications::class;

    public static function getModelLabel(): string
    {
        return __('recruitments::filament/clusters/applications/resources/candidate.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/applications/resources/candidate.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('recruitments::filament/clusters/applications/resources/candidate.navigation.title');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'partner_name',
            'email_from',
            'phone_sanitized',
            'company.name',
            'degree.name',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.title'))
                            ->schema([
                                Forms\Components\TextInput::make('partner_name')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.full-name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email_from')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.email'))
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone_sanitized')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.phone'))
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('linkedin_profile')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.linkedin'))
                                    ->url()
                                    ->maxLength(255),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.title'))
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.company')),
                                Forms\Components\Select::make('degree_id')
                                    ->relationship('degree', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.degree')),
                                Forms\Components\DatePicker::make('availability_date')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.availability-date')),
                                Forms\Components\Select::make('priority')
                                    ->options([
                                        '0' => __('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.priority-options.low'),
                                        '1' => __('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.priority-options.medium'),
                                        '2' => __('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.priority-options.high'),
                                    ])
                                    ->default('1'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.status.title'))
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.status.fields.active'))
                                    ->default(true),
                                Forms\Components\ColorPicker::make('color')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.status.fields.label-color')),
                            ]),
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.communication.title'))
                            ->schema([
                                Forms\Components\TextInput::make('email_cc')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.communication.fields.cc-email'))
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('message_bounced')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.communication.fields.email-bounced'))
                                    ->default(false),
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
                Tables\Columns\TextColumn::make('partner_name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email_from')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone_sanitized')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.phone'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.company'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('degree.name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.degree'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('availability_date')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.availability'))
                    ->date()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('priority')
                    ->options([
                        '0' => __('recruitments::filament/clusters/applications/resources/candidate.table.columns.priority-options.low'),
                        '1' => __('recruitments::filament/clusters/applications/resources/candidate.table.columns.priority-options.medium'),
                        '2' => __('recruitments::filament/clusters/applications/resources/candidate.table.columns.priority-options.high'),
                    ])
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.status'))
                    ->sortable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.label')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.company')),

                Tables\Filters\SelectFilter::make('degree')
                    ->relationship('degree', 'name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.degree')),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        '0' => __('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority-options.low'),
                        '1' => __('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority-options.medium'),
                        '2' => __('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority-options.high'),
                    ])
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.status')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('recruitments::filament/clusters/applications/resources/candidate.table.actions.delete.notification.title'))
                            ->body(__('recruitments::filament/clusters/applications/resources/candidate.table.actions.delete.notification.body'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('recruitments::filament/clusters/applications/resources/candidate.table.bulk-actions.delete.notification.title'))
                                ->body(__('recruitments::filament/clusters/applications/resources/candidate.table.bulk-actions.delete.notification.body'))
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('recruitments::filament/clusters/applications/resources/candidate.table.empty-state-actions.create.notification.title'))
                            ->body(__('recruitments::filament/clusters/applications/resources/candidate.table.empty-state-actions.create.notification.body'))
                    ),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}
