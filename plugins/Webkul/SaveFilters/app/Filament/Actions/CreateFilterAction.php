<?php

namespace Webkul\SavedFilters\Filament\Actions;

use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\Action;
use Filament\Support\Enums\MaxWidth;
use Filament\Forms;
use Webkul\SavedFilters\Models\SavedFilter;

class CreateFilterAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'saved_filters.save.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->model(SavedFilter::class)
            ->form([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->autofocus()
                    ->required(),
                Forms\Components\Select::make('color')
                    ->label('Color')
                    ->options(function() {
                        return collect([
                            'danger' => 'Danger',
                            'gray' => 'Gray',
                            'info' => 'Information',
                            'primary' => 'Primary',
                            'success' => 'Success',
                            'warning' => 'Warning',
                        ])->mapWithKeys(function ($value, $key) {
                            return [
                                $key => '<div class="flex gap-4 items-center"><span class="flex w-5 h-5 rounded-full" style="background: rgb(var(--'.$key.'-500))"></span> '.$value.'</span>'
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
            ])->action(function (): void {
                $model = $this->getModel();

                $record = $this->process(function (array $data) use ($model): SavedFilter {
                    $record = new $model;
                    $record->fill($data);

                    $record->save();

                    return $record;
                });

                $this->record($record);

                $this->success();
            })
            ->successNotificationTitle('Filter created successfully')
            ->hiddenLabel()
            ->icon('heroicon-o-plus')
            ->iconButton()
            ->slideOver()
            ->modalHeading('Save Filter')
            ->modalWidth(MaxWidth::Medium);
    }
}
