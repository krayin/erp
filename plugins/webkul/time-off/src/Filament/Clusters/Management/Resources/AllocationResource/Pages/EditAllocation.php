<?php

namespace Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource\Pages;

use Webkul\TimeOff\Filament\Clusters\Management\Resources\AllocationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Webkul\TimeOff\Enums\State;

class EditAllocation extends EditRecord
{
    protected static string $resource = AllocationResource::class;

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
