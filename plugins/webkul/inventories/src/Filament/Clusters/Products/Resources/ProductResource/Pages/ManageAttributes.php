<?php

namespace Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Products\Resources\ProductResource;
use Webkul\Product\Models\ProductAttribute;

class ManageAttributes extends ManageRelatedRecords
{
    protected static string $resource = ProductResource::class;

    protected static string $relationship = 'attributes';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/products/resources/product/pages/manage-attributes.title');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('attribute_id')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.form.employee'))
                    ->required()
                    ->relationship('attribute', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('options')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.form.employee'))
                    ->required()
                    ->relationship(
                        name: 'options',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn (Forms\Get $get, Builder $query) => $query->where('products_attribute_options.attribute_id', $get('attribute_id')),
                    )
                    ->searchable()
                    ->preload()
                    ->multiple(),
            ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->description(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.description'))
            ->columns([
                Tables\Columns\TextColumn::make('attribute.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.columns.attribute')),
                Tables\Columns\TextColumn::make('values.attributeOption.name')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.columns.values'))
                    ->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Timesheet')
                    ->label(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->after(function ($record) {
                        $this->updateOrCreateVariants($record);
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.header-actions.create.notification.body')),
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->after(function ($record) {
                        $this->updateOrCreateVariants($record);
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.actions.edit.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/products/resources/product/pages/manage-attributes.table.actions.delete.notification.body')),
                    ),
            ])
            ->paginated(false);
    }

    protected function updateOrCreateVariants(ProductAttribute $record): void
    {
        $record->values->each(function ($value) use ($record) {
            $value->update([
                'extra_price'  => $value->attributeOption->extra_price,
                'attribute_id' => $record->attribute_id,
                'product_id'   => $record->product_id,
            ]);
        });

        // foreach ($record->product->attributes as $productAttribute) {
        //     $record->product->variants()->updateOrCreate([
        //         'product_id' => $record->product_id,
        //         'attribute_id' => $productAttribute->attribute_id,
        //     ], [
        //         'price' => $record->product->price,
        //         'sku' => $record->product->sku,
        //         'weight' => $record->product->weight,
        //         'status' => $record->product->status,
        //         'quantity' => $record->product->quantity,
        //         'attribute_id' => $productAttribute->attribute_id,
        //     ]);
        // }
    }
}
