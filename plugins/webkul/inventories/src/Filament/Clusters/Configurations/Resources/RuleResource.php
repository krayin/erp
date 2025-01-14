<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource\Pages\ManageRules;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RuleResource\Pages;
use Webkul\Inventory\Models\Route;
use Webkul\Inventory\Models\Rule;

class RuleResource extends Resource
{
    protected static ?string $model = Rule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 4;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/rule.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/rule.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255)
                            ->autofocus()
                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('action')
                                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.action'))
                                            ->required()
                                            ->options(Enums\RuleAction::class)
                                            ->default(Enums\RuleAction::PULL->value)
                                            ->selectablePlaceholder(false)
                                            ->live(),
                                        Forms\Components\Select::make('picking_type_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.picking-type'))
                                            ->relationship('pickingType', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\Select::make('source_location_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.source-location'))
                                            ->relationship('sourceLocation', 'full_name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\Select::make('destination_location_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.destination-location'))
                                            ->relationship('destinationLocation', 'full_name')
                                            ->searchable()
                                            ->preload()
                                            ->required(),
                                        Forms\Components\Select::make('procure_method')
                                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.supply-method'))
                                            ->required()
                                            ->options(Enums\ProcureMethod::class)
                                            ->selectablePlaceholder(false)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: new HtmlString(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.supply-method-hint-tooltip')))
                                            ->hidden(fn (Forms\Get $get): bool => $get('action') == Enums\RuleAction::PUSH->value),
                                        Forms\Components\Select::make('auto')
                                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.automatic-move'))
                                            ->required()
                                            ->options(Enums\RuleAuto::class)
                                            ->selectablePlaceholder(false)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: new HtmlString(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fields.automatic-move-hint-tooltip')))
                                            ->hidden(fn (Forms\Get $get): bool => $get('action') == Enums\RuleAction::PULL->value),
                                    ]),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('')
                                            ->hiddenLabel()
                                            ->content(new HtmlString('When products are needed in Destination Location, </br>Operation Type are created from Source Location to fulfill the need.')),
                                    ]),
                            ])
                            ->columns(2),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.applicability.title'))
                                            ->schema([
                                                Forms\Components\Select::make('route_id')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.applicability.fields.route'))
                                                    ->relationship(
                                                        'route',
                                                        'name',
                                                    )
                                                    ->searchable()
                                                    ->preload()
                                                    ->required()
                                                    ->getOptionLabelUsing(function ($record) {
                                                        if ($record->route) {
                                                            return $record->route->name;
                                                        }

                                                        return Route::withTrashed()->find($record->route_id)->name;
                                                    }),
                                            ])
                                            ->columns(1),
                                    ])
                                    ->hiddenOn(ManageRules::class),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.propagation.title'))
                                            ->schema([
                                                Forms\Components\Select::make('group_propagation_option')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.propagation.fields.propagation-procurement-group'))
                                                    ->options(Enums\GroupPropagation::class)
                                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: new HtmlString(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.propagation.fields.propagation-procurement-group-hint-tooltip'))),
                                                Forms\Components\Toggle::make('propagate_cancel')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.propagation.fields.cancel-next-move'))
                                                    ->inline(false)
                                                    ->hidden(fn (Forms\Get $get): bool => $get('action') == Enums\RuleAction::PUSH->value),
                                                Forms\Components\Select::make('propagate_warehouse_id')
                                                    ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.propagation.fields.warehouse-to-propagate'))
                                                    ->relationship('propagateWarehouse', 'name')
                                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: new HtmlString(__('inventories::filament/clusters/configurations/resources/rule.form.sections.general.fieldsets.propagation.fields.warehouse-to-propagate-hint-tooltip')))
                                                    ->searchable()
                                                    ->preload()
                                                    ->hidden(fn (Forms\Get $get): bool => $get('action') == Enums\RuleAction::PUSH->value),
                                            ])
                                            ->columns(1),
                                    ]),
                            ])
                            ->columns(2),
                    ]),

                Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/rule.form.sections.options.title'))
                    ->schema([
                        Forms\Components\Select::make('partner_address_id')
                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.options.fields.partner-address'))
                            ->relationship('partnerAddress', 'name')
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: new HtmlString(__('inventories::filament/clusters/configurations/resources/rule.form.sections.options.fields.partner-address-hint-tooltip')))
                            ->searchable()
                            ->preload()
                            ->hidden(fn (Forms\Get $get): bool => $get('action') == Enums\RuleAction::PUSH->value),
                        Forms\Components\TextInput::make('delay')
                            ->label(__('inventories::filament/clusters/configurations/resources/rule.form.sections.options.fields.lead-time'))
                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: new HtmlString(__('inventories::filament/clusters/configurations/resources/rule.form.sections.options.fields.lead-time-hint-tooltip')))
                            ->integer()
                            ->minValue(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sourceLocation.full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('route.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Actions\ViewAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/rule.table.actions.edit.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/rule.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/rule.table.actions.restore.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/rule.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/rule.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/rule.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/rule.table.actions.force-delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/rule.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/rule.table.bulk-actions.restore.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/rule.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/rule.table.bulk-actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/rule.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/rule.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/rule.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRules::route('/'),
            'create' => Pages\CreateRule::route('/create'),
            'view'   => Pages\ViewRule::route('/{record}'),
            'edit'   => Pages\EditRule::route('/{record}/edit'),
        ];
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->withoutGlobalScopes([
    //             SoftDeletingScope::class,
    //         ]);
    // }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['sourceLocation' => function ($query) {
                $query->withTrashed(); // Include soft deleted routes in the relationship
            }])
            ->with(['destinationLocation' => function ($query) {
                $query->withTrashed(); // Include soft deleted routes in the relationship
            }])
            ->with(['route' => function ($query) {
                $query->withTrashed(); // Include soft deleted routes in the relationship
            }]);
    }
}
