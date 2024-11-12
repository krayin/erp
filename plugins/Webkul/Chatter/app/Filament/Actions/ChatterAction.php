<?php

namespace Webkul\Chatter\Filament\Actions;

use App\Models\Chat;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
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
            // ->badge($this->record->filamentComments()->count())
            ->badge(5)
            ->slideOver()
            ->modalContentFooter(fn (): View => view('chatter::filament.widgets.chatter'))
            ->modalHeading('Chatter')
            ->modalWidth(MaxWidth::Medium)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
        // ->visible(fn (): bool => auth()->user()->can('viewAny', Chat::class));
    }
}
