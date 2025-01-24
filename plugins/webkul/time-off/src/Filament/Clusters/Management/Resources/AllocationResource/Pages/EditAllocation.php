<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Webkul\TimeOff\Enums\State;
use Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource;

class EditAllocation extends EditRecord
{
    protected static string $resource = AllocationResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approved')
                ->label('Approved')
                ->color('gray')
                ->hidden(fn($record) => $record->state !== State::CONFIRM->value)
                ->action(function ($record) {
                    $record->update(['state' => State::VALIDATE_TWO->value]);

                    $this->refreshFormData(['state']);
                }),
            Action::make('refuse')
                ->label('Refuse')
                ->color('gray')
                ->hidden(fn($record) => $record->state === State::REFUSE->value)
                ->action(function ($record) {
                    $record->update(['state' => State::REFUSE->value]);

                    $this->refreshFormData(['state']);
                }),
            Action::make('mark_as_ready_to_confirm')
                ->label('Mark as Ready to Confirm')
                ->color('gray')
                ->visible(fn($record) => $record->state === State::REFUSE->value)
                ->action(function ($record) {
                    $record->update(['state' => State::CONFIRM->value]);

                    $this->refreshFormData(['state']);
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
