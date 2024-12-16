<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\Pages;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource\RelationManagers;
use Webkul\Project\Models\Milestone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MilestoneResource extends Resource
{
    protected static ?string $model = Milestone::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('deadline')
                    ->native(false),
                Forms\Components\Toggle::make('is_completed')
                    ->required(),
                Forms\Components\Select::make('project_id')
                    ->relationship('project', 'name')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_completed')
                    ->boolean(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('project.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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
