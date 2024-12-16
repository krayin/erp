<?php

namespace Webkul\Security\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Security\Filament\Resources\TeamResource\Pages;
use Webkul\Security\Models\Team;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getModelLabel(): string
    {
        return __('security::app.filament.resources.team.navigation.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('security::app.filament.resources.team.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('security::app.filament.resources.team.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('security::app.filament.resources.team.form.name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('security::app.filament.resources.team.table.name'))
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTeams::route('/'),
        ];
    }
}
