<?php

namespace Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Recruitment\Filament\Clusters\Configurations\Resources\DepartmentResource;

class ViewDepartment extends ViewRecord
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make()
                ->setResource(static::$resource),
            Actions\EditAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('recruitments::filament/clusters/configurations/resources/department/pages/view-department.header-actions.delete.notification.title'))
                        ->body(__('recruitments::filament/clusters/configurations/resources/department/pages/view-department.header-actions.delete.notification.body')),
                ),
        ];
    }
}
