<?php

namespace Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Pages;

use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\PaymentTerm;
use Webkul\Invoice\Enums;
use Webkul\Partner\Models\Partner;
use Webkul\Support\Models\Currency;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions\CancelAction;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions\ResetToDraftAction;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions\SetAsCheckedAction;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions\ConfirmAction;
use Webkul\Invoice\Filament\Clusters\Customer\Resources\InvoiceResource\Actions\PayAction;

class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            PayAction::make(),
            ConfirmAction::make(),
            CancelAction::make(),
            ResetToDraftAction::make(),
            SetAsCheckedAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = Auth::user();

        $data['date'] = now();

        $journal = Journal::where('code', 'INV')->first();

        if ($journal) {
            $data['journal_id'] = $journal->id;
            $data['account_id'] = $journal->default_account_id;
        }

        $data['currency_id'] = $journal?->currency_id
            ?? $journal?->company?->currency_id
            ?? Currency::first()?->id
            ?? 1;
        $data['sort'] = Move::max('sort') + 1;
        $data['company_id'] = $user->default_company_id;

        if ($data['invoice_payment_term_id']) {
            $paymentTerm = PaymentTerm::find($data['invoice_payment_term_id']);

            if ($paymentTerm) {
                $data['invoice_date_due'] = now()->addDays($paymentTerm->discount_days);
            }
        }

        $partner = Partner::find($data['partner_id']);

        if ($partner) {
            $data['partner_shipping_id'] = $data['partner_id'];
            $data['invoice_partner_display_name'] = $partner?->name;

            if ($partner->sub_type == 'company' || ! $partner->parent_id) {
                $data['commercial_partner_id'] = $data['partner_id'];
            } else {
                $data['commercial_partner_id'] = $partner->parent_id->commercial_partner_id;
            }
        }

        $data['partner_bank_id'] = $partner->bankAccounts
            ->filter(fn($bankAccount) => $bankAccount->can_send_money)
            ->first()?->id;

        return $data;
    }
}
