<?php

namespace Webkul\Field\Filament\Tables\Filters;

use Filament\Support\Components\Component;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

class CustomFilters extends Component
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

    protected function getResourceClass(): string
    {
        return $this->resourceClass;
    }

    public function getFilters(): array
    {
        $fields = $this->getFields();

        return $fields->map(function ($field) {
            return $this->createFilter($field);
        })->toArray();
    }

    public function getQueryBuilderConstraints(): array
    {
        $fields = $this->getFields();

        return $fields->map(function ($field) {
            return $this->createConstraint($field);
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

        return $query
            ->orderBy('sort')
            ->whereJsonContains('table_settings', ['setting' => 'filterable'])
            ->get();
    }

    protected function createFilter(Field $field): Tables\Filters\BaseFilter
    {
        $filter = match ($field->type) {
            'checkbox' => Tables\Filters\Filter::make($field->code)
                ->query(fn (Builder $query): Builder => $query->where($field->code, true)),

            'toggle' => Tables\Filters\Filter::make($field->code)
                ->toggle()
                ->query(fn (Builder $query): Builder => $query->where($field->code, true)),

            'radio' => Tables\Filters\SelectFilter::make($field->code)
                ->options(function () use ($field) {
                    return collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray();
                }),

            'select' => $field->is_multiselect
                ? Tables\Filters\SelectFilter::make($field->code)
                    ->options(function () use ($field) {
                        return collect($field->options)
                            ->mapWithKeys(fn ($option) => [$option => $option])
                            ->toArray();
                    })
                    ->query(function (Builder $query, $state) use ($field): Builder {
                        if (empty($state['values'])) {
                            return $query;
                        }

                        return $query->where(function (Builder $query) use ($state, $field) {
                            foreach ((array) $state as $value) {
                                $query->orWhereJsonContains($field->code, $value);
                            }
                        });
                    })
                    ->multiple()
                : Tables\Filters\SelectFilter::make($field->code)
                    ->options(function () use ($field) {
                        return collect($field->options)
                            ->mapWithKeys(fn ($option) => [$option => $option])
                            ->toArray();
                    }),

            'checkbox_list' => Tables\Filters\SelectFilter::make($field->code)
                ->options(function () use ($field) {
                    return collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray();
                })
                ->query(function (Builder $query, $state) use ($field): Builder {
                    if (empty($state['values'])) {
                        return $query;
                    }

                    return $query->where(function (Builder $query) use ($state, $field) {
                        foreach ((array) $state as $value) {
                            $query->orWhereJsonContains($field->code, $value);
                        }
                    });
                })
                ->multiple(),

            default => Tables\Filters\Filter::make($field->code),
        };

        return $filter->label($field->name);
    }

    protected function createConstraint(Field $field): Constraints\Constraint
    {
        $filter = match ($field->type) {
            'text' => match ($field->input_type) {
                'integer' => Constraints\NumberConstraint::make($field->code)->integer(),
                'numeric' => Constraints\NumberConstraint::make($field->code),
                default   => Constraints\TextConstraint::make($field->code),
            },

            'datetime' => Constraints\DateConstraint::make($field->code),

            'checkbox', 'toggle' => Constraints\BooleanConstraint::make($field->code),

            'select' => $field->is_multiselect
                ? Constraints\SelectConstraint::make($field->code)
                    ->options(function () use ($field) {
                        return collect($field->options)
                            ->mapWithKeys(fn ($option) => [$option => $option])
                            ->toArray();
                    })
                    ->multiple()
                : Constraints\SelectConstraint::make($field->code)
                    ->options(function () use ($field) {
                        return collect($field->options)
                            ->mapWithKeys(fn ($option) => [$option => $option])
                            ->toArray();
                    }),

            'checkbox_list' => Constraints\SelectConstraint::make($field->code)
                ->options(function () use ($field) {
                    return collect($field->options)
                        ->mapWithKeys(fn ($option) => [$option => $option])
                        ->toArray();
                })
                ->multiple(),

            default => Constraints\TextConstraint::make($field->code),
        };

        return $filter->label($field->name);
    }
}
