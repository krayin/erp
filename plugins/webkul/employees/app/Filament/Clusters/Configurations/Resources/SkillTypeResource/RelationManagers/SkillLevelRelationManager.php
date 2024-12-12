<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\SkillTypeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Tables as CustomTables;

class SkillLevelRelationManager extends RelationManager
{
    protected static string $relationship = 'skillLevels';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Forms\Components\TextInput::make('level')
                    ->label('Level')
                    ->numeric()
                    ->rules(['numeric', 'min:0', 'max:100'])
                    ->required(),
                Forms\Components\Toggle::make('default_level')
                    ->label('Default Level')
                    ->required(),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                CustomTables\Columns\ProgressBarEntry::make('level')
                    ->getStateUsing(fn ($record) => $record->level),
                Tables\Columns\IconColumn::make('default_level')
                    ->sortable()
                    ->label('Default Level')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->date()
                    ->collapsible(),
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
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
                        }),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
