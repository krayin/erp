<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Webkul\Chatter\Filament\Actions\ChatterAction;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\ReceiptResource;
use Webkul\Inventory\Models\Move;
use Webkul\Inventory\Models\MoveLine;
use Webkul\Inventory\Models\Operation;
use Webkul\Inventory\Enums;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): Notification
    {
        return Notification::make()
            ->success()
            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.notification.title'))
            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.notification.body'));
    }

    protected function getHeaderActions(): array
    {
        return [
            ChatterAction::make()
                ->setResource(static::$resource),
            Actions\Action::make('todo')
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.label'))
                ->requiresConfirmation()
                ->action(function (Operation $record) {
                    if (! $record->moves->count()) {
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.warning.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.warning.body'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $record->update(['state' => Enums\OperationState::READY]);

                    $record->moves()->update(['state' => Enums\MoveState::CONFIRMED]);

                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.success.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.todo.notification.success.body'))
                        ->success()
                        ->send();
                })
                ->hidden(fn () => $this->getRecord()->state !== Enums\OperationState::DRAFT),
            Actions\Action::make('validate')
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.validate.label'))
                ->color('gray')
                ->action(function (Operation $record) {
                    if (! $this->haveSufficientQty($record)) {
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.validate.notification.warning.title'))
                            ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.validate.notification.warning.body'))
                            ->warning()
                            ->send();

                        return;
                    }

                    $record->update(['state' => Enums\OperationState::DONE]);

                    $record->moves()->update(['state' => Enums\MoveState::DONE]);
                }),
            Actions\Action::make('return')
                ->label(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.return.label'))
                ->color('gray'),
            Actions\DeleteAction::make()
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.title'))
                        ->body(__('inventories::filament/clusters/operations/resources/receipt/pages/edit-receipt.header-actions.delete.notification.body')),
                ),
        ];
    }

    public function haveSufficientQty(Operation $record): bool
    {
        return true;

        foreach ($record->moves as $move) {
            if ($move->product->quantities->sum('qty') < $move->product_qty) {
                return false;
            }
        }

        return true;
    }
}
