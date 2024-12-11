<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources\DepartureReasonResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\DepartureReasonResource;
use Webkul\Employee\Models\DepartureReason;

class ListDepartureReasons extends ListRecords
{
    protected static string $resource = DepartureReasonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['sequence'] = DepartureReason::max('sequence') + 1;

                    $data['reason_code'] = crc32($data['name']) % 100000;

                    return $data;
                }),
        ];
    }
}
