<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\LocationResource;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\StorageCategoryResource;
use Webkul\Inventory\Settings\WarehouseSettings;

class ManageLocations extends ManageRelatedRecords
{
    protected static string $resource = StorageCategoryResource::class;

    protected static string $relationship = 'locations';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    /**
     * @param  array<string, mixed>  $parameters
     */
    public static function canAccess(array $parameters = []): bool
    {
        $canAccess = parent::canAccess($parameters);

        if (! $canAccess) {
            return false;
        }

        return app(WarehouseSettings::class)->enable_locations;
    }

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-locations.title');
    }

    public function form(Form $form): Form
    {
        return LocationResource::form($form);
    }

    public function table(Table $table): Table
    {
        return LocationResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-locations.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->modalWidth('6xl')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/storage-category/pages/manage-locations.table.header-actions.create.notification.title'))
                            ->body(__('projects::filament/resources/project/pages/manage-milestones.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
