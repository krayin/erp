<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Webkul\Chatter\Filament\Actions\Chatter\FollowerActions\AddFollowerAction;

class FollowerAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'follower.action';
    }

    public function record(Model|Closure|null $record): static
    {
        $this->record = $record;

        return $this;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-user')
            ->modalIcon('heroicon-s-user')
            ->color('gray')
            ->modal()
            ->badge(fn(Model $record): int => $record->followers->count())
            ->modalContentFooter(fn(Model $record): View => view('chatter::filament.widgets.followers', compact('record')))
            ->modalHeading(__('chatter::app.filament.actions.chatter.follower.modal.heading'))
            ->modalWidth(MaxWidth::Large)
            ->modalSubmitAction(false)
            ->slideOver()
            ->modalCancelAction(false)
            ->modalFooterActions([
                AddFollowerAction::make('addFollower')
                    ->record($this->record),
            ]);
    }
}
