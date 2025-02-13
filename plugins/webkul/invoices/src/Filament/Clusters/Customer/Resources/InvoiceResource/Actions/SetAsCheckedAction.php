<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Webkul\Account\Models\Move;
use Webkul\Invoice\Enums\MoveState;

class SetAsCheckedAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.set-as-checked';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Set as checked'))
            ->color('gray')
            ->action(function (Move $record): void {
                $record->checked = true;
                $record->save();
            })
            ->hidden(function (Move $record) {
                return
                    $record->checked
                    || $record->state == MoveState::DRAFT->value;
            });
    }
}
