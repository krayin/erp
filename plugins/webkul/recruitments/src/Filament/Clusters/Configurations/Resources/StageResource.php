<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Recruitment\Filament\Clusters\Configurations;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\StageResource\Pages;
use Webkul\Recruitment\Models\Stage;

class StageResource extends Resource
{
    protected static ?string $model = Stage::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/stage.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/stage.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('recruitments::filament/clusters/configurations/resources/stage.navigation.title');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'createdBy.name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('recruitments::filament/clusters/configurations/resources/stage.global-search.name')       => $record->name ?? '—',
            __('recruitments::filament/clusters/configurations/resources/stage.global-search.created-by') => $record->createdBy?->name ?? '—',
        ];
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Section::make(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.general-information.title'))
                                        ->schema([
                                            Forms\Components\Hidden::make('creator_id')
                                                ->default(Auth::id())
                                                ->required(),
                                            Forms\Components\TextInput::make('name')
                                                ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.general-information.fields.stage-name'))
                                                ->required(),
                                            Forms\Components\TextInput::make('sort')
                                                ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.general-information.fields.sort'))
                                                ->numeric()
                                                ->default(Stage::max('sort') + 1)
                                                ->required(),
                                            Forms\Components\RichEditor::make('requirements')
                                                ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.general-information.fields.requirements'))
                                                ->maxLength(255)
                                                ->columnSpanFull(),
                                        ])->columns(2),
                                ]),
                        ])
                        ->columnSpan(['lg' => 2]),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Section::make(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.title'))
                                ->description(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.description'))
                                ->schema([
                                    Forms\Components\TextInput::make('legend_normal')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.fields.gray-label'))
                                        ->required()
                                        ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.fields.gray-label-tooltip'))
                                        ->default('In Progress'),
                                    Forms\Components\TextInput::make('legend_blocked')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.fields.red-label'))
                                        ->required()
                                        ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.fields.red-label-tooltip'))
                                        ->hintColor('danger')
                                        ->default('Blocked'),
                                    Forms\Components\TextInput::make('legend_done')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.fields.green-label'))
                                        ->required()
                                        ->hintIcon('heroicon-o-question-mark-circle', tooltip: __('recruitments::filament/clusters/configurations/resources/stage.form.sections.tooltips.fields.green-label-tooltip'))
                                        ->hintColor('success')
                                        ->default('Ready for Next Stage'),
                                ]),
                            Forms\Components\Section::make(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.additional-information.title'))
                                ->schema([
                                    Forms\Components\Select::make('recruitments_job_positions')
                                        ->relationship('jobs', 'name')
                                        ->multiple()
                                        ->preload()
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.additional-information.fields.job-positions')),
                                    Forms\Components\Toggle::make('fold')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.additional-information.fields.folded')),
                                    Forms\Components\Toggle::make('hired_stage')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.additional-information.fields.hired-stage')),
                                    Forms\Components\Toggle::make('is_default')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.form.sections.additional-information.fields.default-stage')),
                                ]),
                        ])
                        ->columnSpan(['lg' => 1]),
                ])
                ->columns(3),
        ])
            ->columns('full');
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.id'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('name')
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.name'))
                ->sortable()
                ->searchable(),
            Tables\Columns\TextColumn::make('jobs.name')
                ->placeholder('-')
                ->badge()
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.job-positions')),
            Tables\Columns\IconColumn::make('is_default')
                ->boolean()
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.default-stage')),
            Tables\Columns\IconColumn::make('fold')
                ->boolean()
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.folded')),
            Tables\Columns\IconColumn::make('hired_stage')
                ->boolean()
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.hired-stage')),
            Tables\Columns\TextColumn::make('createdBy.name')
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.created-by'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.created-at'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
            Tables\Columns\TextColumn::make('updated_at')
                ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.columns.updated-at'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable(),
        ])
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('name')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.name'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('jobs')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.job-position'))
                            ->multiple()
                            ->icon('heroicon-o-briefcase')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\BooleanConstraint::make('fold')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.folded'))
                            ->icon('heroicon-o-briefcase'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('legend_normal')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.gray-label')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('legend_blocked')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.red-label')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('legend_done')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.green-label')),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('createdBy')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.created-by'))
                            ->multiple()
                            ->icon('heroicon-o-user')
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.filters.updated-at')),
                    ]),
            ])
            ->filtersFormColumns(2)
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.stage-name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('fold')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.folded'))
                    ->collapsible(),
                Tables\Grouping\Group::make('legend_normal')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.gray-label'))
                    ->collapsible(),
                Tables\Grouping\Group::make('legend_blocked')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.red-label'))
                    ->collapsible(),
                Tables\Grouping\Group::make('legend_done')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.green-label'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('recruitments::filament/clusters/configurations/resources/stage.table.empty-state-actions.create.label'))
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('recruitments::filament/clusters/configurations/resources/stage.table.actions.delete.notification.title'))
                            ->body(__('recruitments::filament/clusters/configurations/resources/stage.table.actions.delete.notification.body'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('recruitments::filament/clusters/configurations/resources/stage.table.bulk-actions.delete.notification.title'))
                                ->body(__('recruitments::filament/clusters/configurations/resources/stage.table.bulk-actions.delete.notification.body'))
                        ),
                ]),
            ])
            ->reorderable('sort', 'Desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Grid::make(['default' => 3])
                    ->schema([
                        Components\Group::make()
                            ->schema([
                                Components\Section::make(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.general-information.title'))
                                    ->schema([
                                        Components\TextEntry::make('name')
                                            ->icon('heroicon-o-cube')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.general-information.entries.stage-name')),
                                        Components\TextEntry::make('sort')
                                            ->placeholder('—')
                                            ->icon('heroicon-o-bars-3-bottom-right')
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.general-information.entries.sort')),
                                        Components\TextEntry::make('requirements')
                                            ->icon('heroicon-o-document-text')
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.general-information.entries.requirements'))
                                            ->placeholder('—')
                                            ->html()
                                            ->columnSpanFull(),
                                    ])->columns(2),
                                Components\Section::make(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.additional-information.title'))
                                    ->schema([
                                        Components\TextEntry::make('jobs.name')
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.additional-information.entries.job-positions'))
                                            ->badge()
                                            ->listWithLineBreaks()
                                            ->placeholder('—'),
                                        Components\IconEntry::make('fold')
                                            ->boolean()
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.additional-information.entries.folded')),
                                        Components\IconEntry::make('hired_stage')
                                            ->boolean()
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.additional-information.entries.hired-stage')),
                                        Components\IconEntry::make('is_default')
                                            ->boolean()
                                            ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.additional-information.entries.default-stage')),
                                    ]),
                            ])->columnSpan(2),
                        Components\Group::make([
                            Components\Section::make(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.tooltips.title'))
                                ->schema([
                                    Components\TextEntry::make('legend_normal')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.tooltips.entries.gray-label'))
                                        ->icon('heroicon-o-information-circle')
                                        ->placeholder('—'),
                                    Components\TextEntry::make('legend_blocked')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.tooltips.entries.red-label'))
                                        ->icon('heroicon-o-x-circle')
                                        ->iconColor('danger')
                                        ->placeholder('—'),
                                    Components\TextEntry::make('legend_done')
                                        ->label(__('recruitments::filament/clusters/configurations/resources/stage.infolist.sections.tooltips.entries.green-label'))
                                        ->icon('heroicon-o-check-circle')
                                        ->iconColor('success')
                                        ->placeholder('—'),
                                ]),

                        ])->columnSpan(1),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListStages::route('/'),
            'create' => Pages\CreateStage::route('/create'),
            'edit'   => Pages\EditStage::route('/{record}/edit'),
            'view'   => Pages\ViewStages::route('/{record}'),
        ];
    }
}
