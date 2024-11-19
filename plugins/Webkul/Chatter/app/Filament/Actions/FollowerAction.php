<?php

namespace Webkul\Chatter\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\View;

class FollowerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'chatter.follower';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-user-plus')
            ->color('gray')
            ->modalContent(fn (Model $record): View => view('chatter::filament.widgets.followers', compact('record')))
            ->modalHeading('Invite Followers')
            ->modalWidth(MaxWidth::Large)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
