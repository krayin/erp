<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Employee\Filament\Resources\EmployeeResource;
use Webkul\Partner\Models\Partner;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $partner = $this->record->partner;

        return [
            ...$data,
            ...$partner ? $partner->toArray() : [],
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return [
            ...$data,
            'creator_id' => Auth::user()->id,
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record = parent::handleRecordUpdate($record, $data);

        $partner = Partner::updateOrCreate(
            [
                'id' => $record->partner_id,
            ],
            [
                'name'         => $data['name'] ?? null,
                'avatar'       => $data['avatar'] ?? null,
                'email'        => ($data['work_email'] ?? $data['private_email']) ?? null,
                'job_title'    => $data['job_title'] ?? null,
                'phone'        => $data['work_phone'] ?? null,
                'mobile'       => $data['mobile_phone'] ?? null,
                'color'        => $data['color'] ?? null,
                'parent_id'    => $data['parent_id'] ?? null,
                'creator_id'   => $record->creator_id,
                'company_id'   => $data['company_id'],
                'account_type' => 'individual',
            ]
        );

        if ($record->partner_id !== $partner->id) {
            $record->partner_id = $partner->id;
            $record->save();
        }

        return $record;
    }
}
