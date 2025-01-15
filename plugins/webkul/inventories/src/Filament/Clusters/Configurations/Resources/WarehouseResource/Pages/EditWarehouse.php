<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Inventory\Enums\DeliveryStep;
use Webkul\Inventory\Enums\ReceptionStep;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;
use Webkul\Inventory\Models\Location;

class EditWarehouse extends EditRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/edit-warehouse.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/edit-warehouse.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/edit-warehouse.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/edit-warehouse.header-actions.delete.notification.body')),
                ),
        ];
    }

    protected function afterSave(): void
    {
        $warehouse = $this->getRecord();

        $this->updateLocations(
            'reception_steps',
            [
                ReceptionStep::ONE_STEP->value => [
                    'archive' => [$warehouse->input_stock_location_id, $warehouse->qc_stock_location_id],
                ],
                ReceptionStep::TWO_STEPS->value => [
                    'restore' => [$warehouse->input_stock_location_id],
                    'archive' => [$warehouse->qc_stock_location_id],
                ],
                ReceptionStep::THREE_STEPS->value => [
                    'restore' => [$warehouse->input_stock_location_id, $warehouse->qc_stock_location_id],
                ],
            ]
        );

        $this->updateLocations(
            'delivery_steps',
            [
                DeliveryStep::ONE_STEP->value => [
                    'archive' => [$warehouse->output_stock_location_id, $warehouse->pack_stock_location_id],
                ],
                DeliveryStep::TWO_STEPS->value => [
                    'restore' => [$warehouse->output_stock_location_id],
                    'archive' => [$warehouse->pack_stock_location_id],
                ],
                DeliveryStep::THREE_STEPS->value => [
                    'restore' => [$warehouse->output_stock_location_id, $warehouse->pack_stock_location_id],
                ],
            ]
        );
    }

    private function updateLocations(string $stepType, array $steps): void
    {
        $currentStep = $this->data[$stepType] ?? null;

        if (! $currentStep || ! isset($steps[$currentStep])) {
            return;
        }

        $actions = $steps[$currentStep];

        if (isset($actions['archive'])) {
            Location::withTrashed()->whereIn('id', $actions['archive'])->update(['deleted_at' => now()]);
        }

        if (isset($actions['restore'])) {
            Location::withTrashed()->whereIn('id', $actions['restore'])->update(['deleted_at' => null]);
        }
    }
}
