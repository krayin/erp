<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class FollowerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'follower.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-user-plus')
            ->color('gray')
            ->modal()
            // ->badge(fn (Model $record): int => $record->followers()->count())
            ->badge(fn (Model $record): int => 5)
            ->modalContentFooter(fn (Model $record): View => view('chatter::filament.widgets.followers', compact('record')))
            ->modalHeading(__('chatter::app.filament.actions.chatter.follower.modal.heading'))
            ->modalWidth(MaxWidth::Large)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
