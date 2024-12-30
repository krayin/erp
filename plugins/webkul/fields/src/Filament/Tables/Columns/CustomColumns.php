<?php

namespace Webkul\Field\Filament\Tables\Columns;

use Filament\Support\Components\Component;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

class CustomColumns extends Component
{
    protected array $include = [];

    protected array $exclude = [];

    protected ?string $resourceClass = null;

    final public function __construct(string $resource)
    {
        $this->resourceClass = $resource;
    }

    public static function make(string $resource): static
    {
        $static = app(static::class, ['resource' => $resource]);

        $static->configure();

        return $static;
    }

    public function include(array $fields): static
    {
        $this->include = $fields;

        return $this;
    }

    public function exclude(array $fields): static
    {
        $this->exclude = $fields;

        return $this;
    }

    public function getColumns(): array
    {
        $fields = $this->getFields();

        return $fields->map(function ($field) {
            return $this->createColumn($field);
        })->toArray();
    }

    protected function getFields(): Collection
    {
        $query = Field::query()
            ->where('customizable_type', $this->getResourceClass()::getModel())
            ->where('use_in_table', true);

        if (! empty($this->include)) {
            $query->whereIn('code', $this->include);
        }

        if (! empty($this->exclude)) {
            $query->whereNotIn('code', $this->exclude);
        }

        return $query->orderBy('sort')->get();
    }

    protected function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    protected function createColumn(Field $field): Tables\Columns\Column
    {
        $columnClass = match ($field->type) {
            'text', 'textarea', 'select', 'radio' => Tables\Columns\TextColumn::class,
            'checkbox', 'toggle' => Tables\Columns\IconColumn::class,
            'checkbox_list' => Tables\Columns\TextColumn::class,
            'datetime'      => Tables\Columns\TextColumn::class,
            'editor', 'markdown' => Tables\Columns\TextColumn::class,
            'color' => Tables\Columns\ColorColumn::class,
            default => Tables\Columns\TextColumn::class,
        };

        $column = $columnClass::make($field->code)
            ->label($field->name);

        if (! empty($field->table_settings)) {
            foreach ($field->table_settings as $setting) {
                $this->applySetting($column, $setting);
            }
        }

        return $column;
    }

    protected function applySetting(Tables\Columns\Column $column, array $setting): void
    {
        $name = $setting['setting'];
        $value = $setting['value'] ?? null;

        if (method_exists($column, $name)) {
            if ($value !== null) {
                if ($name == 'weight') {
                    $column->{$name}(constant(FontWeight::class."::$value"));
                } elseif ($name == 'size') {
                    $column->{$name}(constant(TextColumnSize::class."::$value"));
                } else {
                    $column->{$name}($value);
                }
            } else {
                $column->{$name}();
            }
        }
    }
}
