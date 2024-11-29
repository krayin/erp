<?php

namespace Webkul\TableViews\Filament\Actions;

use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms;
use Filament\Support\Enums\MaxWidth;
use Webkul\TableViews\Models\TableView;
use Webkul\TableViews\Models\TableViewFavorite;

class EditViewAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'table_views.update.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(TableView::class)
            ->fillForm(function (array $arguments): array {
                $tableViewFavorite = TableViewFavorite::query()
                    ->where('user_id', auth()->id())
                    ->where('view_type', 'saved')
                    ->where('view_key', $arguments['view_model']['id'])
                    ->first();

                return [
                    'name'        => $arguments['view_model']['name'],
                    'color'       => $arguments['view_model']['color'],
                    'icon'        => $arguments['view_model']['icon'],
                    'is_favorite' => $tableViewFavorite?->is_favorite ?? false,
                    'is_public'   => $arguments['view_model']['is_public'],
                ];
            })
            ->form([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->autofocus()
                    ->required(),
                Forms\Components\Select::make('color')
                    ->label('Color')
                    ->options(function () {
                        return collect([
                            'danger'  => 'Danger',
                            'gray'    => 'Gray',
                            'info'    => 'Information',
                            'success' => 'Success',
                            'warning' => 'Warning',
                        ])->mapWithKeys(function ($value, $key) {
                            return [
                                $key => '<div class="flex gap-4 items-center"><span class="flex w-5 h-5 rounded-full" style="background: rgb(var(--'.$key.'-500))"></span> '.$value.'</span>',
                            ];
                        });
                    })
                    ->native(false)
                    ->allowHtml(),
                \Guava\FilamentIconPicker\Forms\IconPicker::make('icon')
                    ->label('Icon')
                    ->sets(['heroicons'])
                    ->columns(4)
                    ->preload()
                    ->optionsLimit(50),
                Forms\Components\Toggle::make('is_favorite')
                    ->label('Add To Favorites')
                    ->helperText('Add this filter to your favorites'),
                Forms\Components\Toggle::make('is_public')
                    ->label('Make Public')
                    ->helperText('Make this filter available to all users'),
            ])->action(function (array $arguments): void {
                TableView::find($arguments['view_model']['id'])->update($arguments['view_model']);

                $record = $this->process(function (array $data) use ($arguments): TableView {
                    $record = TableView::find($arguments['view_model']['id']);
                    $record->fill($data);

                    $record->save();

                    TableViewFavorite::updateOrCreate(
                        ['view_type' => 'saved', 'view_key' => $arguments['view_model']['id'], 'user_id' => auth()->id()],
                        ['is_favorite' => $data['is_favorite']]
                    );

                    return $record;
                });

                $this->record($record);

                $this->success();
            })
            ->label('Edit View')
            ->successNotificationTitle('View updated successfully')
            ->icon('heroicon-s-pencil-square')
            ->slideOver()
            ->modalHeading('Edit View')
            ->modalWidth(MaxWidth::Medium);
    }
}
