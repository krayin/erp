<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\RelationManagers;
use Webkul\Support\Models\ActivityPlan;

class ActivityPlanResource extends Resource
{
    protected static ?string $model = ActivityPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 5;

    protected static ?string $cluster = Configurations::class;

    public static function getNavigationLabel(): string
    {
        return __('projects::filament/clusters/configurations/resources/activity-plan.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('projects::filament/clusters/configurations/resources/activity-plan.form.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('projects::filament/clusters/configurations/resources/activity-plan.form.status'))
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.columns.status'))
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.groups.status'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('projects::filament/clusters/configurations/resources/activity-plan.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('projects::filament/clusters/configurations/resources/activity-plan.table.actions.restore.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/activity-plan.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('projects::filament/clusters/configurations/resources/activity-plan.table.actions.delete.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/activity-plan.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('projects::filament/clusters/configurations/resources/activity-plan.table.actions.force-delete.notification.title'))
                            ->body(__('projects::filament/clusters/configurations/resources/activity-plan.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/clusters/configurations/resources/activity-plan.table.bulk-actions.restore.notification.title'))
                                ->body(__('projects::filament/clusters/configurations/resources/activity-plan.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/clusters/configurations/resources/activity-plan.table.bulk-actions.delete.notification.title'))
                                ->body(__('projects::filament/clusters/configurations/resources/activity-plan.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('projects::filament/clusters/configurations/resources/activity-plan.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('projects::filament/clusters/configurations/resources/activity-plan.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->where('plugin', 'projects');
            });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('General Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('projects::filament/clusters/configurations/resources/activity-plan.infolist.name'))
                            ->icon('heroicon-o-briefcase')
                            ->placeholder('—'),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label(__('projects::filament/clusters/configurations/resources/activity-plan.infolist.status'))
                            ->boolean(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivityTemplateRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListActivityPlans::route('/'),
            'view'   => Pages\ViewActivityPlan::route('/{record}'),
            'edit'   => Pages\EditActivityPlan::route('/{record}/edit'),
        ];
    }
}
