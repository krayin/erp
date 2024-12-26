<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Project\Filament\Resources\ProjectResource;
use Filament\Notifications\Notification;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ChatterActions\ChatterAction::make(),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Project deleted')
                        ->body('The project has been deleted successfully.'),
                ),
            Actions\EditAction::make(),
        ];
    }
}
