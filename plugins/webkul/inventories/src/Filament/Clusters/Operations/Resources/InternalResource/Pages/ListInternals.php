<?php

namespace Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Webkul\Inventory\Filament\Clusters\Operations\Resources\InternalResource;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Enums\OperationState;
use Webkul\TableViews\Filament\Components\PresetView;
use Webkul\TableViews\Filament\Concerns\HasTableViews;

class ListInternals extends ListRecords
{
    use HasTableViews;

    protected static string $resource = InternalResource::class;

    public function getTitle(): string|Htmlable
    {
        return __('inventories::filament/clusters/operations/resources/internal.navigation.title');
    }

    public function getPresetTableViews(): array
    {
        return [
            'todo_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.todo'))
                ->favorite()
                ->icon('heroicon-s-clipboard-document-list')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotIn('state', [OperationState::DONE, OperationState::CANCELED])),
            'my_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.my'))
                ->favorite()
                ->icon('heroicon-s-user')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('user_id', Auth::id())),
            'favorite_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.starred'))
                ->favorite()
                ->icon('heroicon-s-star')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_favorite', true)),
            'draft_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.draft'))
                ->favorite()
                ->icon('heroicon-s-pencil-square')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::DRAFT)),
            'waiting_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.waiting'))
                ->favorite()
                ->icon('heroicon-s-clock')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::WAITING)),
            'ready_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.ready'))
                ->favorite()
                ->icon('heroicon-s-play-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::READY)),
            'done_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.done'))
                ->favorite()
                ->icon('heroicon-s-check-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::DONE)),
            'canceled_receipts' => PresetView::make(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.tabs.canceled'))
                ->icon('heroicon-s-x-circle')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('state', OperationState::CANCELED)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label(__('inventories::filament/clusters/operations/resources/internal/pages/list-internals.header-actions.create.label'))
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
