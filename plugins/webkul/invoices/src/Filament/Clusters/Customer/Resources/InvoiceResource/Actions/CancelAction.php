<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Account\Models\Move;
use Webkul\Invoice\Enums\MoveState;
use Webkul\Invoice\Enums\MoveType;

class CancelAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.cancel';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Cancel'))
            ->color('gray')
            ->action(function (Move $record, Component $livewire): void {
                $record->state = MoveState::CANCEL->value;
                $record->save();

                $livewire->refreshFormData(['state']);
            })
            ->hidden(function (Move $record) {
                return
                    $record->state !== MoveState::DRAFT->value
                    || $record->move_type !== MoveType::OUT_INVOICE->value;
            });
    }
}
