<?php

namespace Webkul\SavedFilters\Filament\Traits;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Webkul\SavedFilters\Components\PresetFilter;
use Webkul\SavedFilters\Components\SavedFilter;
use Webkul\SavedFilters\Models\SavedFilter as SavedFilterModel;
use Webkul\SavedFilters\Filament\Actions\SaveFilterAction;

trait HasSavedFilters
{
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
        $this->resetTable();

        $this->resetPage();

        $this->resetTableSearch();

        $this->resetTableSort();

        $this->resetTableGrouping();

        $this->activeSavedFilter = $tabKey;
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
        return SavedFilterModel::all()
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
            ->map(function (PresetFilter $presetFilter, string | int $key) {
                return $presetFilter->favorite($presetFilter->isFavorite());
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
        return SaveFilterAction::make('saveFilter')
          ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();

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
            ->after(function (): void {
                unset($this->cachedSavedFilters);
                unset($this->cachedFavoriteSavedFilters);
                
                $this->getCachedSavedFilters();
                $this->getCachedFavoriteSavedFilters();
                
                $this->dispatch('filtered-list-updated');
            });
    }
}