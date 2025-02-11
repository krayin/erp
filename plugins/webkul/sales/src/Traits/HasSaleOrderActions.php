<?php

namespace Webkul\Sale\Traits;


use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Route;
use Webkul\Sale\Enums\OrderState;
use Webkul\Chatter\Filament\Actions as ChatterActions;
use Webkul\Partner\Models\Partner;
use Webkul\Sale\Enums\InvoiceStatus;
use Webkul\Sale\Mail\SaleOrderQuotation;
use Webkul\Support\Services\EmailService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Webkul\Sale\Mail\SaleOrderCancelQuotation;

trait HasSaleOrderActions
{
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        $actions = [
            ChatterActions\ChatterAction::make()
                ->setResource($this->getResource()),
            Action::make('confirm')
                ->color('gray')
                ->hidden(fn($record) => $record->state != OrderState::DRAFT->value)
                ->action(function ($record) {
                    $record->update([
                        'state' => OrderState::SALE->value,
                        'invoice_status' => InvoiceStatus::TO_INVOICE->value,
                    ]);

                    $this->refreshFormData(['state']);

                    Notification::make()
                        ->success()
                        ->title('Quotation confirmed')
                        ->body('The quotation has been confirmed and converted to a sale.')
                        ->send();
                }),
            Action::make('backToQuotation')
                ->label('Set as Quotation')
                ->color('gray')
                ->hidden(fn($record) => $record->state != OrderState::CANCEL->value)
                ->action(function ($record) {
                    $record->update([
                        'state' => OrderState::DRAFT->value,
                        'invoice_status' => InvoiceStatus::NO->value,
                    ]);

                    $this->refreshFormData(['state']);

                    Notification::make()
                        ->success()
                        ->title('Quotation Draft')
                        ->body('The quotation has been set as draft.')
                        ->send();
                }),
            Action::make('preview')
                ->modalIcon('heroicon-s-document-text')
                ->modalHeading(__('Preview Quotation'))
                ->modalWidth(MaxWidth::SevenExtraLarge)
                ->modalFooterActions(function ($record) {
                    return [];
                })
                ->modalContent(function ($record) {
                    return view('sales::sales.quotation', ['record' => $record]);
                })
                ->color('gray'),
            Action::make('createInvoice')
                ->modalIcon('heroicon-s-receipt-percent')
                ->modalHeading(__('Preview Quotation'))
                ->hidden(fn($record) => $record->invoice_status != InvoiceStatus::TO_INVOICE->value)
                ->action(function () {})
                ->modalWidth(MaxWidth::SevenExtraLarge),
            Action::make('sendByEmail')
                ->beforeFormFilled(function ($record, Action $action) {
                    $pdf = Pdf::loadView('sales::sales.quotation', compact('record'))
                        ->setPaper('A4', 'portrait')
                        ->setOption('defaultFont', 'Arial');

                    $fileName = "$record->name-" . time() . ".pdf";
                    $filePath = 'sales-orders/' . $fileName;

                    Storage::disk('public')->put($filePath, $pdf->output());

                    $action->fillForm([
                        'file' => $filePath,
                        'partners' => [$record->partner_id],
                        'subject' => $record->partner->name . ' Quotation (Ref ' . $record->name . ')',
                        'description' => 'Dear ' . $record->partner->name . ', <br/><br/>Your quotation <strong>' . $record->name . '</strong> amounting in <strong>' . $record->currency->symbol . ' ' . $record->amount_total . '</strong> is ready for review.<br/><br/>Should you have any questions or require further assistance, please feel free to reach out to us.',
                    ]);
                })
                ->form(
                    function (Form $form, $record) {
                        return $form->schema([
                            Forms\Components\Select::make('partners')
                                ->options(Partner::all()->pluck('name', 'id'))
                                ->multiple()
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('subject')
                                ->placeholder('Subject')
                                ->hiddenLabel(),
                            Forms\Components\RichEditor::make('description')
                                ->placeholder('Description')
                                ->hiddenLabel(),
                            Forms\Components\FileUpload::make('file')
                                ->label('Attachment')
                                ->downloadable()
                                ->openable()
                                ->disk('public')
                                ->hiddenLabel(),
                        ]);
                    }
                )
                ->modalIcon('heroicon-s-envelope')
                ->modalHeading('Send Quotation by Email')
                ->hidden(fn($record) => $record->state != OrderState::DRAFT->value)
                ->action(function ($record, array $data) {
                    $this->handleSendByEmail($record, $data);
                }),
            Action::make('cancelQuotation')
                ->color('gray')
                ->label('Cancel')
                ->modalIcon('heroicon-s-x-circle')
                ->modalHeading(__('Cancel Quotation'))
                ->modalFooterActions(function ($record, $livewire): array {
                    return [
                        Action::make('sendAndCancel')
                            ->label(__('Cancel and Send Email'))
                            ->icon('heroicon-o-envelope')
                            ->modalIcon('heroicon-s-envelope')
                            ->action(function () use ($record, $livewire) {
                                $record->update([
                                    'state' => OrderState::CANCEL->value,
                                    'invoice_status' => InvoiceStatus::NO->value,
                                ]);

                                if ($livewire?->mountedActionsData[0]) {
                                    $this->handleCancelAndSendEmail($record, $livewire?->mountedActionsData[0]);
                                }

                                $this->refreshFormData(['state']);

                                Notification::make()
                                    ->success()
                                    ->title('Quotation cancelled')
                                    ->body('The quotation has been cancelled.')
                                    ->send();
                            })
                            ->cancelParentActions(),
                        Action::make('cancel')
                            ->label('Cancel')
                            ->icon('heroicon-o-x-circle')
                            ->modalIcon('heroicon-s-x-circle')
                            ->action(function () use ($record) {
                                $record->update([
                                    'state' => OrderState::CANCEL->value,
                                    'invoice_status' => InvoiceStatus::NO->value,
                                ]);

                                $this->refreshFormData(['state']);

                                Notification::make()
                                    ->success()
                                    ->title('Quotation cancelled')
                                    ->body('The quotation has been cancelled.')
                                    ->send();
                            })
                            ->cancelParentActions(),
                        Action::make('close')
                            ->color('gray')
                            ->label('Close')
                            ->cancelParentActions(),
                    ];
                })
                ->form(
                    function (Form $form, $record) {
                        return $form->schema([
                            Forms\Components\Select::make('partners')
                                ->options(Partner::all()->pluck('name', 'id'))
                                ->multiple()
                                ->default([$record->partner_id])
                                ->searchable()
                                ->preload(),
                            Forms\Components\TextInput::make('subject')
                                ->default(fn() => 'Quotation ' . $record->name . ' has been cancelled for Sales Order #' . $record->id)
                                ->placeholder('Subject')
                                ->hiddenLabel(),
                            Forms\Components\RichEditor::make('description')
                                ->placeholder('Description')
                                ->default(function () use ($record) {
                                    return 'Dear ' . $record->partner->name . ', <br/><br/>We would like to inform you that your Sales Order ' . $record->name . ' has been cancelled. As a result, no further charges will apply to this order. If a refund is required, it will be processed at the earliest convenience.<br/><br/>Should you have any questions or require further assistance, please feel free to reach out to us.';
                                })
                                ->hiddenLabel(),
                        ]);
                    }
                )
                ->hidden(fn($record) => ! in_array($record->state, [OrderState::DRAFT->value, OrderState::SENT->value, OrderState::SALE->value])),
            Actions\DeleteAction::make(),
        ];

        if (method_exists($this, 'getAdditionalHeaderActions')) {
            $actions = [
                ...$actions,
                ...$this->getAdditionalHeaderActions(),
            ];
        }

        return $actions;
    }

    private function preparePayloadForSendByEmail($record, $partner, $data)
    {
        $modalName = match ($record->state) {
            OrderState::DRAFT->value => 'Quotation',
            OrderState::SALE->value => 'Sales Order',
            OrderState::SENT->value => 'Quotation',
            OrderState::CANCEL->value => 'Quotation',
            default => 'Quotation',
        };

        return [
            'record_url'     => $this->getRedirectUrl() ?? '',
            'record_name'    => $record->name,
            'model_name'     => $modalName,
            'subject'        => $data['subject'],
            'description'    => $data['description'],
            'to'             => [
                'address' => $partner?->email,
                'name'    => $partner?->name,
            ],
        ];
    }

    private function handleSendByEmail($record, $data)
    {
        $partners = Partner::whereIn('id', $data['partners'])->get();

        foreach ($partners as $key => $partner) {
            app(EmailService::class)->send(
                mailClass: SaleOrderQuotation::class,
                view: $viewName = 'sales::mails.sale-order-quotation',
                payload: $this->preparePayloadForSendByEmail($record, $partner, $data),
                attachments: [
                    [
                        'path' => $path = asset(Storage::url($data['file'])),
                        'name' => basename($data['file']),
                    ],
                ]
            );
        }

        $record->state = OrderState::SENT->value;
        $record->save();

        $messageData = [
            'from' => [
                'company' => Auth::user()->defaultCompany->toArray(),
            ],
            'body' => view($viewName, [
                'payload' => $this->preparePayloadForSendByEmail($record, $partner, $data),
            ])->render(),
            'type' => 'comment',
        ];

        $record->addMessage($messageData, Auth::user()->id);

        $this->refreshFormData(['state']);

        Notification::make()
            ->success()
            ->title('Mail Sent')
            ->body('The mail has been sent successfully.')
            ->send();
    }

    private function preparePayloadForCancelAndSendEmail($record, $partner, $data): array
    {
        return [
            'record_url'     => $this->getRedirectUrl() ?? '',
            'record_name'    => $record->name,
            'model_name'     => 'Quotation',
            'subject'        => $data['subject'],
            'description'    => $data['description'],
            'to'             => [
                'address' => $partner?->email,
                'name'    => $partner?->name,
            ],
        ];
    }

    private function handleCancelAndSendEmail($record, $data)
    {
        $partners = Partner::whereIn('id', $data['partners'])->get();

        foreach ($partners as $key => $partner) {
            app(EmailService::class)->send(
                mailClass: SaleOrderCancelQuotation::class,
                view: $viewName = 'sales::mails.sale-order-cancel-quotation',
                payload: $this->preparePayloadForCancelAndSendEmail($record, $partner, $data),
            );
        }

        $messageData = [
            'from' => [
                'company' => Auth::user()->defaultCompany->toArray(),
            ],
            'body' => view($viewName, [
                'payload' => $this->preparePayloadForCancelAndSendEmail($record, $partner, $data),
            ])->render(),
            'type' => 'comment',
        ];

        $record->addMessage($messageData, Auth::user()->id);
    }
}
