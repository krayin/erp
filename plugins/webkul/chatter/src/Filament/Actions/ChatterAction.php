<?php

namespace Webkul\Chatter\Filament\Actions;

use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

class ChatterAction extends Action
{
    protected mixed $activityPlans;

    protected string $modelName;

    public static function getDefaultName(): ?string
    {
        return 'chatter.action';
    }

    public function setModelName(string $modelName): static
    {
        $this->modelName = $modelName;

        return $this;
    }

    public function getModelName(): string
    {
        return $this->modelName;
    }

    public function setActivityPlans(mixed $activityPlans): static
    {
        $this->activityPlans = $activityPlans;

        return $this;
    }

    public function getActivityPlans(): mixed
    {
        return $this->activityPlans ?? collect();
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
                'modelName'     => $this->getModelName(),
            ]))
            ->modalHeading(__('chatter::filament/resources/actions/chatter-action.title'))
            ->modalDescription(__('chatter::filament/resources/actions/chatter-action.description'))
            ->badge(fn(Model $record): int => $record->messages()->count())
            ->modalWidth(MaxWidth::TwoExtraLarge)
            ->modalSubmitAction(false)
            ->modalCancelAction(false);
    }
}
