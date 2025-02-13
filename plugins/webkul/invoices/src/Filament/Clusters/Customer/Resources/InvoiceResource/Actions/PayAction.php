<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions;

use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms;
use Livewire\Component;
use Webkul\Account\Enums\PaymentState;
use Webkul\Account\Models\Move;
use Webkul\Invoice\Enums\MoveState;
use Webkul\Invoice\Enums\MoveType;

class PayAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'customers.invoice.pay';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('Pay'))
            ->color('primary')
            ->form(function (Form $form) {
                return $form->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Select::make('journal_id')
                                ->relationship(
                                    'journal',
                                    'name',
                                    fn($query) => $query->whereIn('type', ['bank', 'cash'])
                                )
                                ->searchable()
                                ->preload()
                                ->required(),
                            Forms\Components\TextInput::make('amount')
                                ->prefix(fn($record) => $record->currency->symbol ?? '')
                                ->formatStateUsing(fn($record) => number_format($record->moveLines->sum('price_total'), 2))
                                ->required(),

                        ])->columns(2),
                ]);
            })
            ->action(function (Move $record, Component $livewire): void {})
            ->hidden(function (Move $record) {
                return
                    $record->state != MoveState::POSTED->value
                    || ! in_array($record->payment_state, [PaymentState::NOT_PAID->value, PaymentState::PARTIAL->value, PaymentState::IN_PAYMENT->value])
                    || ! in_array($record->move_type, [MoveType::OUT_INVOICE->value, MoveType::OUT_REFUND->value, MoveType::IN_INVOICE->value, MoveType::IN_REFUND->value, MoveType::OUT_RECEIPT->value, MoveType::IN_RECEIPT->value]);
            });
    }
}
