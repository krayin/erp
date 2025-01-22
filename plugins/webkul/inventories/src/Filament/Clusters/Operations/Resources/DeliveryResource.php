<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Inventory\Enums;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\DeliveryResource\Pages;
use Webkul\Inventory\Models\Operation;
use Filament\Resources\Pages\Page;

class DeliveryResource extends Resource
{
    protected static ?string $model = Operation::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Operations::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/delivery.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/delivery.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return OperationResource::form($form);
    }

    public static function table(Table $table): Table
    {
        return OperationResource::table($table)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('inventories::filament/clusters/operations/resources/delivery.table.actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/delivery.table.actions.delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/delivery.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/delivery.table.bulk-actions.delete.notification.body')),
                    ),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereHas('operationType', function (Builder $query) {
                    $query->where('type', Enums\OperationType::OUTGOING);
                });
            });
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewDelivery::class,
            Pages\EditDelivery::class,
            Pages\ManageMoves::class,
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
            'index'  => Pages\ListDeliveries::route('/'),
            'create' => Pages\CreateDelivery::route('/create'),
            'view'   => Pages\ViewDelivery::route('/{record}/view'),
            'edit'   => Pages\EditDelivery::route('/{record}/edit'),
            'moves'  => Pages\ManageMoves::route('/{record}/moves'),
        ];
    }
}
