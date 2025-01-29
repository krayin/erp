<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\IncoTermResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Invoice\Models\Incoterm;

class IncoTermResource extends Resource
{
    protected static ?string $model = Incoterm::class;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/incoterm.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/incoterm.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('invoices::filament/clusters/configurations/resources/incoterm.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'code',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('invoices::filament/clusters/configurations/resources/incoterm.global-search.name') => $record->name ?? '—',
            __('invoices::filament/clusters/configurations/resources/incoterm.global-search.code') => $record->code ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('creator_id')
                    ->default(Auth::id())
                    ->required(),
                Forms\Components\TextInput::make('code')
                    ->label(__('invoices::filament/clusters/configurations/resources/incoterm.form.fields.code'))
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/incoterm.form.fields.name'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('invoices::filament/clusters/configurations/resources/incoterm.table.columns.code'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/incoterm.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/incoterm.table.columns.created-by'))
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title(__('invoices::filament/clusters/configurations/resources/incoterm.table.actions.edit.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/incoterm.table.actions.edit.notification.title'))
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title(__('invoices::filament/clusters/configurations/resources/incoterm.table.actions.delete.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/incoterm.table.actions.delete.notification.title'))
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title(__('invoices::filament/clusters/configurations/resources/incoterm.table.actions.restore.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/incoterm.table.actions.restore.notification.title'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->title(__('invoices::filament/clusters/configurations/resources/incoterm.table.bulk-actions.delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/incoterm.table.bulk-actions.delete.notification.title'))
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->title(__('invoices::filament/clusters/configurations/resources/incoterm.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/incoterm.table.bulk-actions.force-delete.notification.title'))
                        ),
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->title(__('invoices::filament/clusters/configurations/resources/incoterm.table.bulk-actions.restore.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/incoterm.table.bulk-actions.restore.notification.title'))
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('code')
                    ->placeholder(__('invoices::filament/clusters/configurations/resources/incoterm.infolist.entries.code')),
                Infolists\Components\TextEntry::make('name')
                    ->placeholder(__('invoices::filament/clusters/configurations/resources/incoterm.infolist.entries.name')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIncoTerms::route('/'),
        ];
    }
}
