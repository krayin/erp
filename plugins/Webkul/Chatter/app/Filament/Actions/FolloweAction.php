<?php

namespace Webkul\Chatter\Filament\Actions;

use App\Models\Chat;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\View\View;

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
            ->color('gray')
            ->slideOver()
            ->modalContentFooter(fn (Model $record): View => view('chatter::filament.widgets.chatter', compact('record')))
            ->modalHeading('Chatter')
            ->modalWidth(MaxWidth::Large)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
