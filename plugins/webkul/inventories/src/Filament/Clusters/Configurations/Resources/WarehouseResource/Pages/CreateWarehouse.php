<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;
use Webkul\Inventory\Models\Location;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Enums\ReceptionStep;
use Webkul\Inventory\Enums\DeliveryStep;

class CreateWarehouse extends CreateRecord
{
    protected static string $resource = WarehouseResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getCreatedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/configurations/resources/warehouse/pages/create-warehouse.notification.title'))
            ->body(__('inventories::filament/clusters/configurations/resources/warehouse/pages/create-warehouse.notification.body'));
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = Auth::id();

        $data['company_id'] = $data['company_id'] ?? Auth::user()->default_company_id;

        $locationsData = [
            [
                'type'         => LocationType::VIEW,
                'name'         => $data['code'],
                'full_name'    => $data['code'],
                'barcode'      => NULL,
                'is_scrap'     => false,
                'is_replenish' => false,
                'parent_id'    => 1,
                'creator_id'   => 1,
                'company_id'   => $data['company_id'],
                'deleted_at'   => NULL,
            ], [
                'type'         => LocationType::INTERNAL,
                'name'         => 'Stock',
                'full_name'    => $data['code'].'/Stock',
                'barcode'      => $data['code'].'STOCK',
                'is_scrap'     => false,
                'is_replenish' => true,
                'parent_id'    => 11,
                'creator_id'   => 1,
                'company_id'   => $data['company_id'],
                'deleted_at'   => NULL,
            ], [
                'type'         => LocationType::INTERNAL,
                'name'         => 'Input',
                'full_name'    => $data['code'].'/Input',
                'barcode'      => $data['code'].'INPUT',
                'is_scrap'     => false,
                'is_replenish' => false,
                'parent_id'    => 11,
                'creator_id'   => 1,
                'company_id'   => $data['company_id'],
                'deleted_at'   => ReceptionStep::TWO_STEPS === $data['reception_steps'] ? NULL : now(),
            ], [
                'type'         => LocationType::INTERNAL,
                'name'         => 'Quality Control',
                'full_name'    => $data['code'].'/Quality Control',
                'barcode'      => $data['code'].'QUALITY',
                'is_scrap'     => false,
                'is_replenish' => false,
                'parent_id'    => 11,
                'creator_id'   => 1,
                'company_id'   => $data['company_id'],
                'deleted_at'   => ReceptionStep::THREE_STEPS === $data['reception_steps'] ? NULL : now(),
            ], [
                'type'         => LocationType::INTERNAL,
                'name'         => 'Output',
                'full_name'    => $data['code'].'/Output',
                'barcode'      => $data['code'].'OUTPUT',
                'is_scrap'     => false,
                'is_replenish' => false,
                'parent_id'    => 11,
                'creator_id'   => 1,
                'company_id'   => $data['company_id'],
                'deleted_at'   => DeliveryStep::TWO_STEPS === $data['delivery_steps'] ? NULL : now(),
            ], [
                'type'         => LocationType::INTERNAL,
                'name'         => 'Packing Zone',
                'full_name'    => $data['code'].'/Packing Zone',
                'barcode'      => $data['code'].'PACKING',
                'is_scrap'     => false,
                'is_replenish' => false,
                'parent_id'    => 11,
                'creator_id'   => 1,
                'company_id'   => $data['company_id'],
                'deleted_at'   => DeliveryStep::THREE_STEPS === $data['delivery_steps'] ? NULL : now(),
            ],
        ];

        $locations = [];

        foreach ($locationsData as $location) {
            $locations[] = Location::create($location);
        }

        $data['view_location_id'] = $locations[0]->id;
        $data['lot_stock_location_id'] = $locations[1]->id;
        $data['input_stock_location_id'] = $locations[2]->id;
        $data['qc_stock_location_id'] = $locations[3]->id;
        $data['output_stock_location_id'] = $locations[4]->id;
        $data['pack_stock_location_id'] = $locations[5]->id;

        return $data;
    }
 
    protected function afterCreate(): void
    {
        $this->getRecord()->viewLocation()->withTrashed()->first()->update([
            'warehouse_id' => $this->getRecord()->id,
        ]);

        $this->getRecord()->lotStockLocation()->withTrashed()->first()->update([
            'warehouse_id' => $this->getRecord()->id,
        ]);

        $this->getRecord()->inputStockLocation()->withTrashed()->first()->update([
            'warehouse_id' => $this->getRecord()->id,
        ]);

        $this->getRecord()->qcStockLocation()->withTrashed()->first()->update([
            'warehouse_id' => $this->getRecord()->id,
        ]);

        $this->getRecord()->outputStockLocation()->withTrashed()->first()->update([
            'warehouse_id' => $this->getRecord()->id,
        ]);

        $this->getRecord()->packStockLocation()->withTrashed()->first()->update([
            'warehouse_id' => $this->getRecord()->id,
        ]);
    }
}
