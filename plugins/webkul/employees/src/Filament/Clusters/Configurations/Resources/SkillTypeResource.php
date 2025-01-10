<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\Pages;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\RelationManagers;
use Webkul\Employee\Models\SkillType;

class SkillTypeResource extends Resource
{
    protected static ?string $model = SkillType::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationGroup = 'Employee';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    public static function getModelLabel(): string
    {
        return __('employees::filament/clusters/configurations/resources/skill-type.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('employees::filament/clusters/configurations/resources/skill-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('employees::filament/clusters/configurations/resources/skill-type.navigation.title');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'createdBy.name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [

            __('employees::filament/clusters/configurations/resources/skill-type.global-search.name')       => $record->name ?? '—',
            __('employees::filament/clusters/configurations/resources/skill-type.global-search.created-by') => $record->createdBy?->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label(__('employees::filament/clusters/configurations/resources/skill-type.form.sections.fields.name'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->placeholder('Enter skill type name'),
                    Forms\Components\Hidden::make('creator_id')
                        ->default(Auth::user()->id),
                    Forms\Components\Select::make('color')
                        ->label(__('employees::filament/clusters/configurations/resources/skill-type.form.sections.fields.color'))
                        ->options(function () {
                            return collect(Enums\Colors::options())->mapWithKeys(function ($value, $key) {
                                return [
                                    $key => '<div class="flex items-center gap-4"><span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$key.'-500))"></span> '.$value.'</span>',
                                ];
                            });
                        })
                        ->native(false)
                        ->allowHtml(),
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('employees::filament/clusters/configurations/resources/skill-type.form.sections.fields.status'))
                        ->default(true),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('color')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.color'))
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->formatStateUsing(fn (SkillType $skillType) => '<span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$skillType->color.'-500))"></span>')
                    ->html()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skills.name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.skills'))
                    ->badge()
                    ->color(fn (SkillType $skillType) => $skillType->color)
                    ->searchable(),
                Tables\Columns\TextColumn::make('skillLevels.name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.levels'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.status'))
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.created-by'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->columnToggleFormColumns(2)
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.filters.status')),
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('skillLevels')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.filters.skill-levels'))
                            ->icon('heroicon-o-bolt')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('skills')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.filters.skills'))
                            ->icon('heroicon-o-bolt')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('createdBy')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.filters.created-by'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.filters.updated-at')),
                    ]),
            ])
            ->filtersFormColumns(2)
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('color')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.groups.color'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.groups.status'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type.table.actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type.table.actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type.table.actions.restore.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type.table.bulk-actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type.table.bulk-actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('employees::filament/clusters/configurations/resources/skill-type.table.empty-state-actions.create.notification.title'))
                            ->body(__('employees::filament/clusters/configurations/resources/skill-type.table.empty-state-actions.create.notification.body')),
                    )
                    ->after(function ($record) {
                        return redirect(
                            self::getUrl('edit', ['record' => $record])
                        );
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SkillsRelationManager::class,
            RelationManagers\SkillLevelRelationManager::class,
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->placeholder('—')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.infolist.sections.entries.name')),
                        Infolists\Components\TextEntry::make('color')
                            ->placeholder('—')
                            ->html()
                            ->formatStateUsing(fn (SkillType $skillType) => '<span class="flex h-5 w-5 rounded-full" style="background: rgb(var(--'.$skillType->color.'-500))"></span>')
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.infolist.sections.entries.color')),
                        Infolists\Components\IconEntry::make('is_active')
                            ->boolean()
                            ->label(__('employees::filament/clusters/configurations/resources/skill-type.infolist.sections.entries.status')),
                    ])->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkillTypes::route('/'),
            'view'  => Pages\ViewSkillType::route('/{record}'),
            'edit'  => Pages\EditSkillType::route('/{record}/edit'),
        ];
    }
}
