<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\Pages;
use Webkul\Project\Filament\Resources\ProjectResource\Pages\ManageMilestones;
use Webkul\Project\Filament\Resources\ProjectResource\RelationManagers\MilestonesRelationManager;
use Webkul\Project\Models\Milestone;
use Webkul\Project\Settings\TaskSettings;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Configurations::class;

    public static function getNavigationLabel(): string
    {
        return __('projects::app.filament.clusters.configurations.resources.milestone.navigation.title');
    }

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(TaskSettings::class)->enable_milestones;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('deadline')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.form.deadline'))
                    ->native(false),
                Forms\Components\Toggle::make('is_completed')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.form.is-completed'))
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.form.project'))
                    ->relationship('project', 'name')
                    ->hiddenOn([
                        MilestonesRelationManager::class,
                        ManageMilestones::class,
                    ])
                    ->required()
                    ->searchable()
                    ->preload(),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.deadline'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_completed')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.is-completed'))
                    ->beforeStateUpdated(function ($record, $state) {
                        $record->completed_at = $state ? now() : null;
                    }),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.completed-at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.project'))
                    ->hiddenOn([
                        MilestonesRelationManager::class,
                        ManageMilestones::class,
                    ])
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.creator'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('project.name')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.groups.project')),
                Tables\Grouping\Group::make('is_completed')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.groups.is-completed')),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.groups.created-at'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label('Is Completed')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.filters.is-completed')),
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.filters.project'))
                    ->relationship('project', 'name')
                    ->hiddenOn([
                        MilestonesRelationManager::class,
                        ManageMilestones::class,
                    ])
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label(__('projects::app.filament.clusters.configurations.resources.milestone.table.filters.creator'))
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Milestone updated')
                            ->body('The milestone has been updated successfully.'),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Milestone deleted')
                            ->body('The milestone has been deleted successfully.'),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Milestones deleted')
                                ->body('The milestones has been deleted successfully.'),
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageMilestones::route('/'),
        ];
    }
}
