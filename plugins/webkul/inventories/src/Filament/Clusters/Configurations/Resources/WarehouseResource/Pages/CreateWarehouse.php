<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\DeliveryStep;
use Webkul\Inventory\Enums\LocationType;
use Webkul\Inventory\Enums\ReceptionStep;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\WarehouseResource;
use Webkul\Inventory\Models\Location;

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

        $data = $this->createLocations($data);

        return $data;
    }

    protected function afterCreate(): void
    {
        Location::withTrashed()->whereIn('id', [
            $this->getRecord()->view_location_id,
            $this->getRecord()->lot_stock_location_id,
            $this->getRecord()->input_stock_location_id,
            $this->getRecord()->qc_stock_location_id,
            $this->getRecord()->output_stock_location_id,
            $this->getRecord()->pack_stock_location_id,
        ])->update(['warehouse_id' => $this->getRecord()->id]);
    }

    protected function createLocations(array $data): array
    {
        $data['view_location_id'] = Location::create([
            'type'         => LocationType::VIEW,
            'name'         => $data['code'],
            'is_scrap'     => false,
            'is_replenish' => false,
            'parent_id'    => 1,
            'creator_id'   => $data['creator_id'],
            'company_id'   => $data['company_id'],
        ])->id;

        $data['lot_stock_location_id'] = Location::create([
            'type'         => LocationType::INTERNAL,
            'name'         => 'Stock',
            'barcode'      => $data['code'].'STOCK',
            'is_scrap'     => false,
            'is_replenish' => true,
            'parent_id'    => $data['view_location_id'],
            'creator_id'   => $data['creator_id'],
            'company_id'   => $data['company_id'],
        ])->id;

        $data['input_stock_location_id'] = Location::create([
            'type'         => LocationType::INTERNAL,
            'name'         => 'Input',
            'barcode'      => $data['code'].'INPUT',
            'is_scrap'     => false,
            'is_replenish' => false,
            'parent_id'    => $data['view_location_id'],
            'creator_id'   => $data['creator_id'],
            'company_id'   => $data['company_id'],
            'deleted_at'   => in_array($data['reception_steps'], [ReceptionStep::TWO_STEPS, ReceptionStep::THREE_STEPS]) ? null : now(),
        ])->id;

        $data['qc_stock_location_id'] = Location::create([
            'type'         => LocationType::INTERNAL,
            'name'         => 'Quality Control',
            'barcode'      => $data['code'].'QUALITY',
            'is_scrap'     => false,
            'is_replenish' => false,
            'parent_id'    => $data['view_location_id'],
            'creator_id'   => $data['creator_id'],
            'company_id'   => $data['company_id'],
            'deleted_at'   => $data['reception_steps'] === ReceptionStep::THREE_STEPS ? null : now(),
        ])->id;

        $data['output_stock_location_id'] = Location::create([
            'type'         => LocationType::INTERNAL,
            'name'         => 'Output',
            'barcode'      => $data['code'].'OUTPUT',
            'is_scrap'     => false,
            'is_replenish' => false,
            'parent_id'    => $data['view_location_id'],
            'creator_id'   => $data['creator_id'],
            'company_id'   => $data['company_id'],
            'deleted_at'   => in_array($data['delivery_steps'], [DeliveryStep::TWO_STEPS, DeliveryStep::THREE_STEPS]) ? null : now(),
        ])->id;

        $data['pack_stock_location_id'] = Location::create([
            'type'         => LocationType::INTERNAL,
            'name'         => 'Packing Zone',
            'barcode'      => $data['code'].'PACKING',
            'is_scrap'     => false,
            'is_replenish' => false,
            'parent_id'    => $data['view_location_id'],
            'creator_id'   => $data['creator_id'],
            'company_id'   => $data['company_id'],
            'deleted_at'   => $data['delivery_steps'] === DeliveryStep::THREE_STEPS ? null : now(),
        ])->id;

        return $data;
    }
}
