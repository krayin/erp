<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TaskStageResource\Pages;
use Webkul\Project\Filament\Resources\ProjectResource\RelationManagers\TaskStagesRelationManager;
use Webkul\Project\Models\TaskStage;
use Filament\Notifications\Notification;

class TaskStageResource extends Resource
{
    protected static ?string $model = TaskStage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->hiddenOn(TaskStagesRelationManager::class)
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
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->hiddenOn(TaskStagesRelationManager::class)
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('project_id')
                    ->relationship('project', 'name')
                    ->hiddenOn(TaskStagesRelationManager::class)
                    ->label('Project')
                    ->searchable()
                    ->preload(),
            ])
            ->groups([
                Tables\Grouping\Group::make('project.name')
                    ->label('Project'),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->date(),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'desc')
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Task stage updated')
                            ->body('The task stage has been updated successfully.'),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Task stage restored')
                            ->body('The task stage has been restored successfully.'),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Task stage deleted')
                            ->body('The task stage has been deleted successfully.'),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Task stage force deleted')
                            ->body('The task stage has been force deleted successfully.'),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task stages deleted')
                                ->body('The task stages has been deleted successfully.'),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task stages force deleted')
                                ->body('The task stages has been force deleted successfully.'),
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Task stages restored')
                                ->body('The task stages has been restored successfully.'),
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTaskStages::route('/'),
        ];
    }
}
