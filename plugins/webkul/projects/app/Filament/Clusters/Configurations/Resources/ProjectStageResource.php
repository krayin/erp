<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource\Pages;
use Webkul\Project\Models\ProjectStage;
use Webkul\Project\Settings\TaskSettings;
use Filament\Notifications\Notification;

class ProjectStageResource extends Resource
{
    protected static ?string $model = ProjectStage::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    public static function isDiscovered(): bool
    {
        if (app()->runningInConsole()) {
            return true;
        }

        return app(TaskSettings::class)->enable_project_stages;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
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
            ])
            ->groups([
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
                            ->title('Project stage updated')
                            ->body('The project stage has been updated successfully.'),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Project stage restored')
                            ->body('The project stage has been restored successfully.'),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Project stage deleted')
                            ->body('The project stage has been deleted successfully.'),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Project stage force deleted')
                            ->body('The project stage force has been deleted successfully.'),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Project stages deleted')
                                ->body('The project stages has been deleted successfully.'),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Project stages force deleted')
                                ->body('The project stages has been force deleted successfully.'),
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Project stages restored')
                                ->body('The project stages has been restored successfully.'),
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProjectStages::route('/'),
        ];
    }
}
