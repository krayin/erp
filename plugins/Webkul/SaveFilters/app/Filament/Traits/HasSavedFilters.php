<?php

namespace Webkul\SavedFilters\Filament\Traits;

use Filament\Tables\Actions\Action;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Enums\ActionSize;
use Filament\Resources\Components\Tab;

trait HasSavedFilters
{
    public function getTabs(): array
    {
        return $this->getSavedFiltersTab();
    }
    
    public function mergeSavedFilters(array $tabs): array
    {
        $tabs = array_merge($tabs, $this->getSavedFiltersTab());

        return $tabs;
    }

    protected function getSavedFiltersTab(): array
    {
        return [
            'default' => Tab::make('Default'),
        ];
    }

    public function getSavedFiltersTriggerAction(): Action
    {
        $action = Action::make('openSavedFilters')
            ->label(__('filament-tables::table.actions.filter.label'))
            ->iconButton()
            ->icon(FilamentIcon::resolve('tables::actions.filter') ?? 'heroicon-m-funnel')
            ->color('gray')
            ->livewireClickHandlerEnabled(false)
            ->modalSubmitAction(false)
            ->extraModalFooterActions([
                $this->getFiltersApplyAction()
                    ->close(),
                Action::make('resetFilters')
                    ->label(__('filament-tables::table.filters.actions.reset.label'))
                    ->color('danger')
                    ->action('resetTableFiltersForm')
                    ->button(),
            ])
            ->modalCancelActionLabel(__('filament::components/modal.actions.close.label'))
            ->table($this);

        if ($this->modifyFiltersTriggerActionUsing) {
            $action = $this->evaluate($this->modifyFiltersTriggerActionUsing, [
                'action' => $action,
            ]) ?? $action;
        }

        if ($action->getView() === Action::BUTTON_VIEW) {
            $action->defaultSize(ActionSize::Small);
        }

        return $action;
    }
}