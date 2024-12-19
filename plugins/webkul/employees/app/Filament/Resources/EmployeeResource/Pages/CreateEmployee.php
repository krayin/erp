<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Resources\EmployeeResource;
use Webkul\Partner\Models\Partner;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            ...$data,
            'creator_id' => Auth::user()->id,
        ];
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);

        $partner = Partner::create([
            'name'         => $data['name'] ?? null,
            'avatar'       => $data['avatar'] ?? null,
            'email'        => ($data['work_email'] ?? $data['private_email']) ?? null,
            'job_title'    => $data['job_title'] ?? null,
            'phone'        => $data['work_phone'] ?? null,
            'mobile'       => $data['mobile_phone'] ?? null,
            'color'        => $data['color'] ?? null,
            'parent_id'    => $data['parent_id'] ?? null,
            'creator_id'   => Auth::user()->id,
            'company_id'   => $data['company_id'],
            'account_type' => 'individual',
        ]);

        $record->partner_id = $partner->id;
        $record->save();

        return $record;
    }
}
