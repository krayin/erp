<?php

namespace Webkul\Chatter\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ChatterAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'chatter.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-chat-bubble-left-right')
            ->modalIcon('heroicon-s-chat-bubble-left-right')
            ->slideOver()
            ->modalContentFooter(fn (Model $record): View => view('chatter::filament.widgets.chatter', compact('record')))
            ->modalHeading(__('chatter::app.filament.actions.chatter.action.modal.label'))
            ->modalDescription(__('chatter::app.filament.actions.chatter.action.modal.description'))
            ->badge(fn (Model $record): int => $record->messages()->count())
            ->modalWidth(MaxWidth::ThreeExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
