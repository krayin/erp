<?php

namespace Webkul\Chatter\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ChatterAction extends Action
{
    protected mixed $activityPlans;

    protected string $resource = '';

    public static function getDefaultName(): ?string
    {
        return 'chatter.action';
    }

    public function setActivityPlans(mixed $activityPlans): static
    {
        $this->activityPlans = $activityPlans;

        return $this;
    }

    public function setResource(string $resource): static
    {
        $this->resource = $resource;

        return $this;
    }

    public function getActivityPlans(): mixed
    {
        return $this->activityPlans ?? collect();
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->hiddenLabel()
            ->icon('heroicon-s-chat-bubble-left-right')
            ->modalIcon('heroicon-s-chat-bubble-left-right')
            ->slideOver()
            ->modalContentFooter(fn(Model $record): View => view('chatter::filament.widgets.chatter', [
                'record'        => $record,
                'activityPlans' => $this->getActivityPlans(),
                'resource'      => $this->getResource(),
            ]))
            ->modalHeading(__('chatter::filament/resources/actions/chatter-action.title'))
            ->modalDescription(__('chatter::filament/resources/actions/chatter-action.description'))
            ->badge(fn(Model $record): int => $record->messages()->count())
            ->modalWidth(MaxWidth::TwoExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
