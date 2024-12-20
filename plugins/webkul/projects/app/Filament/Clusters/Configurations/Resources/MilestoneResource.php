<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\Pages;
use Webkul\Project\Filament\Resources\ProjectResource\Pages\ManageProjectMilestones;
use Webkul\Project\Filament\Resources\ProjectResource\RelationManagers\MilestonesRelationManager;
use Webkul\Project\Models\Milestone;
use Webkul\Project\Settings\TaskSettings;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Configurations::class;

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
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('deadline')
                    ->label('Deadline')
                    ->native(false),
                Forms\Components\Toggle::make('is_completed')
                    ->label('Is Completed')
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->hiddenOn([
                        MilestonesRelationManager::class,
                        ManageProjectMilestones::class,
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
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->label('Deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\ToggleColumn::make('is_completed')
                    ->label('Is Completed')
                    ->beforeStateUpdated(function ($record, $state) {
                        $record->completed_at = $state ? now() : null;
                    }),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->label('Project')
                    ->hiddenOn([
                        MilestonesRelationManager::class,
                        ManageProjectMilestones::class,
                    ])
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_completed')
                    ->label('Is Completed'),
                Tables\Filters\SelectFilter::make('project_id')
                    ->label('Project')
                    ->relationship('project', 'name')
                    ->hiddenOn([
                        MilestonesRelationManager::class,
                        ManageProjectMilestones::class,
                    ])
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('creator_id')
                    ->label('Creator')
                    ->relationship('creator', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->groups([
                Tables\Grouping\Group::make('project.name')
                    ->label('Project'),
                Tables\Grouping\Group::make('is_completed')
                    ->label('Is Completed'),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
