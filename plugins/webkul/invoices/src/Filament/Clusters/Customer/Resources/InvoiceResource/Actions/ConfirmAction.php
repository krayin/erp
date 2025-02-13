<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Livewire\Component;
use Webkul\Account\Models\Move;
use Webkul\Invoice\Enums\AutoPost;
use Webkul\Invoice\Enums\MoveState;

class ConfirmAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.confirm';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Confirm'))
            ->color('gray')
            ->action(function (Move $record, Component $livewire): void {
                if ($record->moveLines()->get()->isEmpty()) {
                    Notification::make()
                        ->warning()
                        ->title(__('Error'))
                        ->body(__('Please add at least one line to the invoice.'))
                        ->send();

                    return;
                }

                $record->state = MoveState::POSTED->value;
                $record->save();

                $livewire->refreshFormData(['state']);
            })
            ->hidden(function (Move $record) {
                return
                    $record->state != MoveState::DRAFT->value ||
                    ($record->auto_post != AutoPost::NO->value && $record->date > now());
            });
    }
}
