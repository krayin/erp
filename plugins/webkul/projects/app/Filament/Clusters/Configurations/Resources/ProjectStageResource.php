<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource\Pages;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ProjectStageResource\RelationManagers;
use Webkul\Project\Models\ProjectStage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProjectStageResource extends Resource
{
    protected static ?string $model = ProjectStage::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Toggle::make('is_collapsed'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_collapsed'),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'asc')
            ->actions([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
