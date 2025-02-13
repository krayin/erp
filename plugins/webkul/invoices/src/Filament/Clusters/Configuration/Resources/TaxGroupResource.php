<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources;

use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\TaxGroupResource\Pages;
use Webkul\Account\Models\TaxGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TaxGroupResource extends Resource
{
    protected static ?string $model = TaxGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/tax-group.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('invoices::filament/clusters/configurations/resources/tax-group.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('invoices::filament/clusters/configurations/resources/tax-group.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'company.name',
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('invoices::filament/clusters/configurations/resources/tax-group.global-search.company') => $record->company?->name ?? '—',
            __('invoices::filament/clusters/configurations/resources/tax-group.global-search.name')    => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.form.sections.fields.company'))
                            ->preload(),
                        Forms\Components\Select::make('country_id')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.form.sections.fields.country'))
                            ->preload(),
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.form.sections.fields.name'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('preceding_subtotal')
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.form.sections.fields.preceding-subtotal'))
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.company'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('country.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.country'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.created-by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('preceding_subtotal')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.preceding-subtotal'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.columns.updated-at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('company.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.groups.company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.groups.country'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('invoices::filament/clusters/configurations/resources/tax-group.table.groups.updated-at'))
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->title(__('invoices::filament/clusters/configurations/resources/tax-group.table.actions.delete.notification.title'))
                            ->body(__('invoices::filament/clusters/configurations/resources/tax-group.table.actions.delete.notification.body'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->title(__('invoices::filament/clusters/configurations/resources/tax-group.table.bulk-actions.delete.notification.title'))
                                ->body(__('invoices::filament/clusters/configurations/resources/tax-group.table.bulk-actions.delete.notification.body'))
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('company.name')
                            ->icon('heroicon-o-building-office-2')
                            ->placeholder('-')
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.infolist.sections.entries.company')),
                        Infolists\Components\TextEntry::make('country.name')
                            ->icon('heroicon-o-globe-alt')
                            ->placeholder('-')
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.infolist.sections.entries.country')),
                        Infolists\Components\TextEntry::make('name')
                            ->icon('heroicon-o-tag')
                            ->placeholder('-')
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.infolist.sections.entries.name')),
                        Infolists\Components\TextEntry::make('preceding_subtotal')
                            ->icon('heroicon-o-rectangle-group')
                            ->placeholder('-')
                            ->label(__('invoices::filament/clusters/configurations/resources/tax-group.infolist.sections.entries.preceding-subtotal')),
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTaxGroups::route('/'),
            'create' => Pages\CreateTaxGroup::route('/create'),
            'view'   => Pages\ViewTaxGroup::route('/{record}'),
            'edit'   => Pages\EditTaxGroup::route('/{record}/edit'),
        ];
    }
}
