<?php

namespace Webkul\SavedFilters\Filament\Traits;

use \Closure;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Webkul\SavedFilters\Components\PresetFilter;
use Webkul\SavedFilters\Components\SavedFilter;
use Webkul\SavedFilters\Models\SavedFilter as SavedFilterModel;
use Webkul\SavedFilters\Filament\Actions\CreateFilterAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Webkul\SavedFilters\Enums\SavedFiltersLayout;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Concerns\EvaluatesClosures;

trait HasSavedFilters
{
    use EvaluatesClosures;

    #[Url]
    public ?string $activeSavedFilter = null;

    /**
     * @var array<string | int, SavedFilter>
     */
    protected array $cachedSavedFilters;

    /**
     * @var array<string | int, PresetFilter | SavedFilter>
     */
    protected array $cachedFavoriteSavedFilters;

    protected string | Closure | null $savedFiltersFormMaxHeight = null;

    protected MaxWidth | string | Closure | null $savedFiltersFormWidth = null;

    protected SavedFiltersLayout | Closure | null $savedFiltersLayout = null;

    public function mount(): void
    {
        parent::mount();

        $this->loadDefaultActiveSavedFilter();
    }

    protected function loadDefaultActiveSavedFilter(): void
    {
        if (filled($this->activeSavedFilter)) {
            return;
        }

        $this->activeSavedFilter = $this->getDefaultActiveSavedFilter();
    }

    public function loadFilter($tabKey): void
    {
        if ($this->activeSavedFilter === $tabKey) {
            return;
        }

        $this->resetSavedFilters();

        $this->activeSavedFilter = $tabKey;
    }

    public function resetSavedFilters()
    {
        $this->resetTable();

        $this->resetPage();

        $this->resetTableSearch();

        $this->resetTableSort();

        $this->resetTableGrouping();

        $this->activeSavedFilter = $this->getDefaultActiveSavedFilter();
    }

    public function resetTableSort()
    {
        $this->tableSortColumn = null;

        $this->tableSortDirection = null;
    }

    public function resetTableGrouping()
    {
        $this->tableGrouping = null;

        $this->tableGroupingDirection = null;
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getPresetFilters(): array
    {
        return [];
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getSavedFilters(): array
    {
        return SavedFilterModel::where('filterable_type', static::class)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('is_public', true);
            })
            ->get()
            ->mapWithKeys(function (SavedFilterModel $savedFilter) {
                return [
                    $savedFilter->id => SavedFilter::make($savedFilter->getKey())
                        ->label($savedFilter->name)
                        ->icon($savedFilter->icon)
                        ->color($savedFilter->color)
                        ->favorite($savedFilter->is_favorite)
                        ->modifyQueryUsing(function () use ($savedFilter) {
                            if (! $savedFilter->filters) {
                                return;
                            }

                            foreach ($savedFilter->filters as $key => $filter) {
                                if (! $filter) {
                                    continue;
                                }

                                $this->{$key} = $filter;
                            }
                        }),
                ];
            })->all();
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getAllFilters(): array
    {
        return $this->getPresetFilters() + $this->getCachedSavedFilters();
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getFavoriteSavedFilters(): array
    {
        return collect($this->getAllFilters())
            ->filter(function (PresetFilter $presetFilter) {
                return $presetFilter->isFavorite();
            })
            ->all();
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getCachedFavoriteSavedFilters(): array
    {
        return $this->cachedFavoriteSavedFilters ??= (
            [
                'default' => PresetFilter::make('default')
                    ->label('Default')
                    ->icon('heroicon-m-queue-list')
                    ->favorite()
            ] + $this->getFavoriteSavedFilters()
        );
    }
    /**
     * @return array<string | int, Tab>
     */
    public function getCachedSavedFilters(): array
    {
        return $this->cachedSavedFilters ??= $this->getSavedFilters();
    }

    public function getDefaultActiveSavedFilter(): string | int | null
    {
        return array_key_first($this->getCachedFavoriteSavedFilters());
    }

    public function updatedActiveSavedFilter(): void
    {
        $this->resetPage();
    }


    protected function modifyQueryWithActiveTab(Builder $query): Builder
    {
        if (blank(filled($this->activeSavedFilter))) {
            return $query;
        }

        $savedFilters = $this->getAllFilters();

        if (! array_key_exists($this->activeSavedFilter, $savedFilters)) {
            return $query;
        }

        return $savedFilters[$this->activeSavedFilter]->modifyQuery($query);
    }
    public function saveFilterAction(): \Filament\Actions\Action
    {
        return CreateFilterAction::make('saveFilter')
          ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();

                $data['filterable_type'] = static::class;

                $data['filters'] = [
                    'tableFilters' => $this->tableFilters,
                    'tableGrouping' => $this->tableGrouping,
                    'tableSearch' => $this->tableSearch,
                    'tableColumnSearches' => $this->tableColumnSearches,
                    'tableSortColumn' => $this->tableSortColumn,
                    'tableSortDirection' => $this->tableSortDirection,
                    'tableRecordsPerPage' => $this->tableRecordsPerPage,
                ];
        
                return $data;
            })
            ->after(function (SavedFilterModel $saveFilter): void {
                unset($this->cachedSavedFilters);
                unset($this->cachedFavoriteSavedFilters);
                
                $this->getCachedSavedFilters();
                $this->getCachedFavoriteSavedFilters();
                
                $this->dispatch('filtered-list-updated');

                $this->activeSavedFilter = $saveFilter->id;
            });
    }


    public function setSavedFiltersFormMaxHeight(string | Closure | null $height): static
    {
        $this->savedFiltersFormMaxHeight = $height;

        return $this;
    }

    public function setSavedFiltersFormWidth(MaxWidth | string | Closure | null $width): static
    {
        $this->savedFiltersFormWidth = $width;

        return $this;
    }

    public function setSavedFiltersLayout(SavedFiltersLayout | Closure | null $savedFiltersLayout): static
    {
        $this->savedFiltersLayout = $savedFiltersLayout;

        return $this;
    }

    public function getSavedFiltersFormMaxHeight(): ?string
    {
        return $this->evaluate($this->savedFiltersFormMaxHeight);
    }

    public function getSavedFiltersFormWidth(): MaxWidth | string | null
    {
        return $this->evaluate($this->savedFiltersFormWidth) ?? MaxWidth::Small;
    }

    public function getSavedFiltersLayout(): SavedFiltersLayout
    {
        return $this->evaluate($this->savedFiltersLayout) ?? SavedFiltersLayout::Dropdown;
    }

    public function getActiveSavedFiltersCount()
    {
        return count($this->getCachedSavedFilters());
    }

    public function getSavedFilterActions(): ActionGroup
    {
        return ActionGroup::make([
        ]);
    }

    public function getSavedFiltersTriggerAction(): Action
    {
        return Action::make('openFilters')
            ->label('Saved Filters')
            ->iconButton()
            ->icon('heroicon-m-queue-list')
            ->color('gray')
            ->livewireClickHandlerEnabled(false)
            ->modalSubmitAction(false);
    }
}