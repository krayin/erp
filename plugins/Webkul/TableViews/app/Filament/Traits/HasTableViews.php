<?php

namespace Webkul\TableViews\Filament\Traits;

use \Closure;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Url;
use Webkul\TableViews\Components\PresetView;
use Webkul\TableViews\Components\SavedView;
use Webkul\TableViews\Models\TableView as TableViewModel;
use Webkul\TableViews\Filament\Actions\CreateViewAction;
use Webkul\TableViews\Filament\Actions\EditViewAction;
use Filament\Actions\Action;
use Webkul\TableViews\Enums\TableViewsLayout;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Concerns\EvaluatesClosures;

trait HasTableViews
{
    use EvaluatesClosures;

    #[Url]
    public ?string $activeTableView = null;

    /**
     * @var array<string | int, TableView>
     */
    protected array $cachedTableViews;

    /**
     * @var array<string | int, PresetView | TableView>
     */
    protected array $cachedFavoriteTableViews;

    protected string | Closure | null $tableViewsFormMaxHeight = null;

    protected MaxWidth | string | Closure | null $tableViewsFormWidth = null;

    protected TableViewsLayout | Closure | null $tableViewsLayout = null;

    public function mount(): void
    {
        parent::mount();

        $this->loadDefaultActiveTableView();
    }

    protected function loadDefaultActiveTableView(): void
    {
        if (filled($this->activeTableView)) {
            return;
        }

        $this->activeTableView = $this->getDefaultActiveTableView();
    }

    public function loadView($tabKey): void
    {
        if ($this->activeTableView === $tabKey) {
            return;
        }

        $this->resetTableViews();

        $this->activeTableView = $tabKey;
    }

    public function resetTableViews()
    {
        $this->resetTable();

        $this->resetPage();

        $this->resetTableSearch();

        $this->resetTableSort();

        $this->resetTableGrouping();

        $this->activeTableView = $this->getDefaultActiveTableView();
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
    public function getPresetTableViews(): array
    {
        return [];
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getSavedTableViews(): array
    {
        return TableViewModel::where('filterable_type', static::class)
            ->where(function ($query) {
                $query->where('user_id', auth()->id())
                    ->orWhere('is_public', true);
            })
            ->get()
            ->mapWithKeys(function (TableViewModel $tableView) {
                return [
                    $tableView->id => SavedView::make($tableView->getKey())
                        ->model($tableView)
                        ->label($tableView->name)
                        ->icon($tableView->icon)
                        ->color($tableView->color)
                        ->favorite($tableView->is_favorite)
                        ->modifyQueryUsing(function () use ($tableView) {
                            if (! $tableView->filters) {
                                return;
                            }

                            foreach ($tableView->filters as $key => $filter) {
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
    public function getAllTableViews(): array
    {
        return $this->getPresetTableViews() + $this->getCachedTableViews();
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getFavoriteTableViews(): array
    {
        return collect($this->getAllTableViews())
            ->filter(function (PresetView $presetView) {
                return $presetView->isFavorite();
            })
            ->all();
    }

    /**
     * @return array<string | int, Tab>
     */
    public function getCachedFavoriteTableViews(): array
    {
        return $this->cachedFavoriteTableViews ??= (
            [
                'default' => PresetView::make('default')
                    ->label('Default')
                    ->icon('heroicon-m-queue-list')
                    ->favorite()
            ] + $this->getFavoriteTableViews()
        );
    }
    /**
     * @return array<string | int, Tab>
     */
    public function getCachedTableViews(): array
    {
        return $this->cachedTableViews ??= $this->getSavedTableViews();
    }

    public function getDefaultActiveTableView(): string | int | null
    {
        return array_key_first($this->getCachedFavoriteTableViews());
    }

    public function updatedActiveTableView(): void
    {
        $this->resetPage();
    }


    protected function modifyQueryWithActiveTab(Builder $query): Builder
    {
        if (blank(filled($this->activeTableView))) {
            return $query;
        }

        $tableViews = $this->getAllTableViews();

        if (! array_key_exists($this->activeTableView, $tableViews)) {
            return $query;
        }

        return $tableViews[$this->activeTableView]->modifyQuery($query);
    }

    public function saveFilterAction(): Action
    {
        return CreateViewAction::make('saveFilter')
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
                    // 'toggledTableColumns' => $this->toggledTableColumns,
                ];
        
                return $data;
            })
            ->after(function (TableViewModel $saveFilter): void {
                unset($this->cachedTableViews);
                unset($this->cachedFavoriteTableViews);
                
                $this->getCachedTableViews();
                $this->getCachedFavoriteTableViews();
                
                $this->dispatch('filtered-list-updated');

                $this->activeTableView = $saveFilter->id;
            });
    }


    public function setTableViewsFormMaxHeight(string | Closure | null $height): static
    {
        $this->tableViewsFormMaxHeight = $height;

        return $this;
    }

    public function setTableViewsFormWidth(MaxWidth | string | Closure | null $width): static
    {
        $this->tableViewsFormWidth = $width;

        return $this;
    }

    public function setTableViewsLayout(TableViewsLayout | Closure | null $tableViewsLayout): static
    {
        $this->tableViewsLayout = $tableViewsLayout;

        return $this;
    }

    public function getTableViewsFormMaxHeight(): ?string
    {
        return $this->evaluate($this->tableViewsFormMaxHeight);
    }

    public function getTableViewsFormWidth(): MaxWidth | string | null
    {
        return $this->evaluate($this->tableViewsFormWidth) ?? MaxWidth::ExtraSmall;
    }

    public function getTableViewsLayout(): TableViewsLayout
    {
        return $this->evaluate($this->tableViewsLayout) ?? TableViewsLayout::Dropdown;
    }

    public function getActiveTableView()
    {
        return $this->activeTableView;
    }

    public function getActiveTableViewsCount()
    {
        $tableViews = $this->getAllTableViews();

        if (isset($tableViews[$this->activeTableView])) {
            return 1;
        }
        
        return 0;
    }

    public function getTableViewsTriggerAction(): Action
    {
        return Action::make('openTableViews')
            ->label('Views')
            ->iconButton()
            ->icon('heroicon-m-queue-list')
            ->color('gray')
            ->livewireClickHandlerEnabled(false)
            ->modalSubmitAction(false);
    }

    public function applyTableViewAction(): Action
    {
        return Action::make('applyTableView')
            ->label('Apply View')
            ->icon('heroicon-s-arrow-small-right')
            ->action(function(array $arguments) {
                $this->resetTableViews();

                $this->activeTableView = $arguments['view'];
            });
    }

    public function addTableViewToFavoritesAction(): Action
    {
        return Action::make('addTableViewToFavorites')
            ->label('Add To Favorites')
            ->icon('heroicon-o-star')
            ->action(function(array $arguments) {
                TableViewModel::find($arguments['view'])->update([
                    'is_favorite' => true,
                ]);

                unset($this->cachedTableViews);
                unset($this->cachedFavoriteTableViews);
            });
    }

    public function removeTableViewFromFavoritesAction(): Action
    {
        return Action::make('removeTableViewFromFavorites')
            ->label('Remove From Favorites')
            ->icon('heroicon-o-minus-circle')
            ->action(function(array $arguments) {
                TableViewModel::find($arguments['view'])->update([
                    'is_favorite' => false,
                ]);

                unset($this->cachedTableViews);
                unset($this->cachedFavoriteTableViews);
            });
    }

    public function editTableViewAction(): Action
    {
        return EditViewAction::make('editTableView')
            ->after(function (): void {
                unset($this->cachedTableViews);
                unset($this->cachedFavoriteTableViews);
                
                $this->getCachedTableViews();
                $this->getCachedFavoriteTableViews();
            });
    }

    public function deleteTableViewAction(): Action
    {
        return Action::make('deleteTableView')
            ->label('Delete View')
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function(array $arguments) {
                TableViewModel::find($arguments['view'])->delete();

                unset($this->cachedTableViews);
                unset($this->cachedFavoriteTableViews);
            });
    }
}