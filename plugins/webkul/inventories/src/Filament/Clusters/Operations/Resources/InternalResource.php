<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources;

use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Inventory\Enums;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Pages\Page;
use Webkul\Inventory\Filament\Clusters\Operations;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;
use Webkul\Inventory\Models\Operation;

class InternalResource extends Resource
{
    protected static ?string $model = Operation::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 3;

    protected static ?string $cluster = Operations::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.group');
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
                                ->title(__('inventories::filament/clusters/operations/resources/internal.table.actions.delete.notification.title'))
                                ->body(__('inventories::filament/clusters/operations/resources/internal.table.actions.delete.notification.body')),
                        ),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/internal.table.bulk-actions.delete.notification.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/internal.table.bulk-actions.delete.notification.body')),
                    ),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                return $query->whereHas('operationType', function (Builder $query) {
                    $query->where('type', Enums\OperationType::INTERNAL);
                });
            });
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewInternal::class,
            Pages\EditInternal::class,
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
            'index'  => Pages\ListInternals::route('/'),
            'create' => Pages\CreateInternal::route('/create'),
            'view'   => Pages\ViewInternal::route('/{record}/view'),
            'edit'   => Pages\EditInternal::route('/{record}/edit'),
            'moves'  => Pages\ManageMoves::route('/{record}/moves'),
        ];
    }
}
