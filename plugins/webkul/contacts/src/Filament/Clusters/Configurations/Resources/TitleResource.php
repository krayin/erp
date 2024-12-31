<?php

namespace Webkul\Contact\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Contact\Filament\Clusters\Configurations;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\TitleResource\Pages;
use Webkul\Partner\Models\Title;

class TitleResource extends Resource
{
    protected static ?string $model = Title::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?int $navigationSort = 2;

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('contacts::filament/clusters/configurations/resources/title.form.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('short_name')
                    ->label(__('contacts::filament/clusters/configurations/resources/title.form.short-name'))
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('contacts::filament/clusters/configurations/resources/title.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('short_name')
                    ->label(__('contacts::filament/clusters/configurations/resources/title.table.columns.short-name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/clusters/configurations/resources/title.table.actions.edit.notification.title'))
                            ->body(__('contacts::filament/clusters/configurations/resources/title.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/clusters/configurations/resources/title.table.actions.delete.notification.title'))
                            ->body(__('contacts::filament/clusters/configurations/resources/title.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/clusters/configurations/resources/title.table.bulk-actions.delete.notification.title'))
                            ->body(__('contacts::filament/clusters/configurations/resources/title.table.bulk-actions.delete.notification.body')),
                    ),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageTitles::route('/'),
        ];
    }
}
