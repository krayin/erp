<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductResource;

// use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
// use Webkul\Inventory\Settings\ProductSettings;

class ManageVariants extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'variants';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function canAccess(array $parameters = []): bool
    {
        $canAccess = parent::canAccess($parameters);

        if (! $canAccess) {
            return false;
        }

        return true;
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/product/pages/manage-variants.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('type')
                    ->default('projects'),
                Forms\Components\DatePicker::make('date')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.form.date'))
                    ->required()
                    ->native(false),
                Forms\Components\Select::make('user_id')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.form.employee'))
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.form.description')),
                Forms\Components\TextInput::make('unit_amount')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.form.time-spent'))
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->helperText(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.form.time-spent-helper-text')),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.table.columns.date'))
                    ->date('Y-m-d'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.table.columns.employee')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.table.columns.description')),
                Tables\Columns\TextColumn::make('unit_amount')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.table.columns.time-spent'))
                    ->formatStateUsing(function ($state) {
                        $hours = floor($state);
                        $minutes = ($hours - $hours) * 60;

                        return $hours . ':' . $minutes;
                    }),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/product/pages/manage-variants.table.actions.delete.notification.body')),
                    ),
            ])
            ->paginated(false);
    }
}
