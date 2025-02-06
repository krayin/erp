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

trait HasQuotationActions
{
    protected function getHeaderActions(): array
    {
        return [
            Action::make('confirm')
                ->color('gray')
                ->hidden(fn($record) => $record->state != OrderState::DRAFT->value)
                ->action(function ($record) {
                    $record->state = OrderState::SALE->value;
                    $record->save();

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
                    $record->state = OrderState::DRAFT->value;
                    $record->save();

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
            Action::make('sendByEmail')
                ->color('gray')
                ->hidden(fn($record) => !in_array($record->state, [OrderState::SENT->value, OrderState::SALE->value]))
                ->action(function ($record) {
                    $record->state = OrderState::SALE->value;
                    $record->save();

                    $this->refreshFormData(['state']);

                    Notification::make()
                        ->success()
                        ->title('Quotation confirmed')
                        ->body('The quotation has been confirmed and converted to a sale.')
                        ->send();
                }),
            Action::make('cancelQuotation')
                ->color('gray')
                ->label('Cancel')
                ->modalIcon('heroicon-s-x-circle')
                ->modalHeading(__('Cancel Quotation'))
                ->form(
                    function (Form $form, $record) {
                        return $form->schema([
                            Forms\Components\TextInput::make('subject')
                                ->default(fn() => 'Quotation ' . $record->name . ' has been cancelled for Sales Order #' . $record->id)
                                ->placeholder('Subject')
                                ->hiddenLabel(),
                            Forms\Components\RichEditor::make('description')
                                ->placeholder('Description')
                                ->default(function () use ($record) {
                                    return 'Dear ' . $record->partner->name . ', <br/><br/>We would like to inform you that your Sales Order ' . $record->id . ' has been cancelled. As a result, no further charges will apply to this order. If a refund is required, it will be processed at the earliest convenience.<br/><br/>Should you have any questions or require further assistance, please feel free to reach out to us.';
                                })
                                ->hiddenLabel(),
                        ]);
                    }
                )
                ->hidden(fn($record) => ! in_array($record->state, [OrderState::DRAFT->value, OrderState::SENT->value, OrderState::SALE->value]))
                ->action(function ($record) {
                    $record->update(['state' => OrderState::CANCEL->value]);

                    $this->refreshFormData(['state']);

                    Notification::make()
                        ->success()
                        ->title('Quotation cancelled')
                        ->body('The quotation has been cancelled.')
                        ->send();
                }),
            Actions\ViewAction::make()
                ->hidden(fn() => str_contains(Route::currentRouteName(), 'view')),
            Actions\EditAction::make()
                ->hidden(fn() => str_contains(Route::currentRouteName(), 'edit')),
            Actions\DeleteAction::make(),
        ];
    }
}
