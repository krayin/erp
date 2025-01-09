<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Webkul\Inventory\Enums\DeliveryStep;
use Webkul\Inventory\Enums\ReceptionStep;
use Webkul\Inventory\Filament\Clusters\Configurations;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;
use Webkul\Inventory\Models\Warehouse;

class WarehouseResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Warehouse::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Configurations::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/configurations/resources/warehouse.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/warehouse.navigation.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.title'))
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->autofocus()
                                    ->placeholder(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.name-placeholder'))
                                    ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;'])
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('code')
                                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.code'))
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.code-placeholder'))
                                    ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.code-hint-tooltip'))
                                    ->unique(ignoreRecord: true),

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('company_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.company'))
                                            ->relationship('company', 'name')
                                            ->required()
                                            ->disabled(fn () => Auth::user()->default_company_id)
                                            ->default(Auth::user()->default_company_id),
                                        Forms\Components\Select::make('partner_address_id')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.general.fields.address'))
                                            ->relationship('partnerAddress', 'name'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.additional.title'))
                            ->visible(! empty($customFormFields = static::getCustomFormFields()))
                            ->schema($customFormFields),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.title'))
                            ->schema([
                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.shipment-management'))
                                    ->schema([
                                        Forms\Components\Radio::make('reception_steps')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.incoming-shipments'))
                                            ->default('internal')
                                            ->options(ReceptionStep::class)
                                            ->default(ReceptionStep::ONE_STEP)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.incoming-shipments-hint-tooltip')),

                                        Forms\Components\Radio::make('delivery_steps')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.outgoing-shipments'))
                                            ->default('internal')
                                            ->options(DeliveryStep::class)
                                            ->default(DeliveryStep::ONE_STEP)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.outgoing-shipments-hint-tooltip')),
                                    ])
                                    ->columns(1),

                                Forms\Components\Fieldset::make(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.resupply-management'))
                                    ->schema([
                                        Forms\Components\CheckboxList::make('supplierWarehouses')
                                            ->label(__('inventories::filament/clusters/configurations/resources/warehouse.form.sections.settings.fields.resupply-from'))
                                            ->relationship('supplierWarehouses', 'name'),
                                    ]),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.company'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('partnerAddress.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.address'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.deleted-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('company.name')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.groups.company'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('inventories::filament/clusters/configurations/resources/warehouse.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.restore.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.force-delete.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.restore.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('inventories::filament/clusters/configurations/resources/warehouse.table.bulk-actions.force-delete.notification.body')),
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
            'index'  => Pages\ListWarehouses::route('/'),
            'create' => Pages\CreateWarehouse::route('/create'),
            'view'   => Pages\ViewWarehouse::route('/{record}'),
            'edit'   => Pages\EditWarehouse::route('/{record}/edit'),
        ];
    }
}
