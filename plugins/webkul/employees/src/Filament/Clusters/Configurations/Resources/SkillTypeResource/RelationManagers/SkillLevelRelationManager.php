<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Support\Filament\Tables as CustomTables;
use Webkul\Support\Filament\Tables\Infolists\ProgressBarEntry;

class SkillLevelRelationManager extends RelationManager
{
    protected static string $relationship = 'skillLevels';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.form.name'))
                    ->required(),
                Forms\Components\Hidden::make('creator_id')
                    ->default(Auth::user()->id),
                Forms\Components\TextInput::make('level')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.form.level'))
                    ->numeric()
                    ->rules(['numeric', 'min:0', 'max:100']),
                Forms\Components\Toggle::make('default_level')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.form.default-level')),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                CustomTables\Columns\ProgressBarEntry::make('level')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.columns.level'))
                    ->getStateUsing(fn ($record) => $record->level)
                    ->color(fn ($record): string => match (true) {
                        $record->level === 100                      => 'success',
                        $record->level >= 50 && $record->level < 80 => 'warning',
                        $record->level < 20                         => 'danger',
                        default                                     => 'info',
                    }),
                Tables\Columns\IconColumn::make('default_level')
                    ->sortable()
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.columns.default-level'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.groups.created-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.filters.deleted-records')),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->modal('form')
                    ->mutateFormDataUsing(function ($data) {
                        if ($data['default_level'] ?? false) {
                            $this->getRelationship()->update(['default_level' => false]);
                        }

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.create.notification.title'))
                            ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make()
                        ->mutateFormDataUsing(function ($data, $record) {
                            if ($data['default_level'] ?? false) {
                                $this->getRelationship()->where('id', '!=', $record->id)->update(['default_level' => false]);
                            }

                            return $data;
                        })
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.edit.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.edit.notification.body')),
                        ),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.delete.notification.body')),
                        ),
                    Tables\Actions\RestoreAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.actions.restore.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.bulk-actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.bulk-actions.force-delete.notification.body')),
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.bulk-actions.restore.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.table.bulk-actions.restore.notification.body')),
                        ),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->placeholder('—')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.infolist.entries.name')),
                ProgressBarEntry::make('level')
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.infolist.entries.level'))
                    ->getStateUsing(fn ($record) => $record->level)
                    ->color(fn ($record): string => match (true) {
                        $record->level === 100                      => 'success',
                        $record->level >= 50 && $record->level < 80 => 'warning',
                        $record->level < 20                         => 'danger',
                        default                                     => 'info',
                    }),
                Infolists\Components\IconEntry::make('default_level')
                    ->boolean()
                    ->label(__('employees::filament/clusters/configurations/resources/skill-type/relation-managers/levels.infolist.entries.default-level')),
            ]);
    }
}
