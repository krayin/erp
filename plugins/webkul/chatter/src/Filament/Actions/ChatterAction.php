<?php

namespace Webkul\Chatter\Filament\Actions;

use Closure;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ChatterAction extends Action
{
    protected mixed $activityPlans;

    protected string $resource = '';

    protected string $followerViewMail = '';

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

    public function setFollowerMailView(string | Closure | null $followerViewMail): static
    {
        $this->followerViewMail = $followerViewMail;

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

    public function getFollowerMailView(): string | Closure | null
    {
        return $this->followerViewMail;
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
                'record'           => $record,
                'activityPlans'    => $this->getActivityPlans(),
                'resource'         => $this->getResource(),
                'followerViewMail' => $this->getFollowerMailView(),
            ]))
            ->modalHeading(__('chatter::filament/resources/actions/chatter-action.title'))
            ->modalDescription(__('chatter::filament/resources/actions/chatter-action.description'))
            ->badge(fn(Model $record): int => $record->messages()->count())
            ->modalWidth(MaxWidth::TwoExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
