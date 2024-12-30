<?php

namespace Webkul\Field\Filament\Forms\Components;

use Filament\Forms;
use Filament\Forms\Components\Component;
use Illuminate\Support\Collection;
use Webkul\Field\Models\Field;

class CustomFields extends Component
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

    public function getSchema(): array
    {
        $fields = $this->getFields();

        return $fields->map(function ($field) {
            return $this->createField($field);
        })->toArray();
    }

    protected function getFields(): Collection
    {
        $query = Field::query()
            ->where('customizable_type', $this->getResourceClass()::getModel());

        if (! empty($this->include)) {
            $query->whereIn('code', $this->include);
        }

        if (! empty($this->exclude)) {
            $query->whereNotIn('code', $this->exclude);
        }

        return $query->orderBy('sort')->get();
    }

    protected function createField(Field $field): Forms\Components\Component
    {
        $componentClass = match ($field->type) {
            'text'          => Forms\Components\TextInput::class,
            'textarea'      => Forms\Components\Textarea::class,
            'select'        => Forms\Components\Select::class,
            'checkbox'      => Forms\Components\Checkbox::class,
            'radio'         => Forms\Components\Radio::class,
            'toggle'        => Forms\Components\Toggle::class,
            'checkbox_list' => Forms\Components\CheckboxList::class,
            'datetime'      => Forms\Components\DateTimePicker::class,
            'editor'        => Forms\Components\RichEditor::class,
            'markdown'      => Forms\Components\MarkdownEditor::class,
            'color'         => Forms\Components\ColorPicker::class,
            default         => Forms\Components\TextInput::class,
        };

        $component = $componentClass::make($field->code)
            ->label($field->name);

        if (! empty($field->form_settings['validations'])) {
            foreach ($field->form_settings['validations'] as $validation) {
                $this->applyValidation($component, $validation);
            }
        }

        if (! empty($field->form_settings['settings'])) {
            foreach ($field->form_settings['settings'] as $setting) {
                $this->applySetting($component, $setting);
            }
        }

        if ($field->type == 'text' && $field->input_type != 'text') {
            $component->{$field->input_type}();
        }

        if (in_array($field->type, ['select', 'radio', 'checkbox_list']) && ! empty($field->options)) {
            $component->options(function () use ($field) {
                return collect($field->options)
                    ->mapWithKeys(fn ($option) => [$option => $option])
                    ->toArray();
            });

            if ($field->is_multiselect) {
                $component->multiple();
            }
        }

        if (in_array($field->type, ['select', 'datetime'])) {
            $component->native(false);
        }

        return $component;
    }

    protected function applyValidation(Forms\Components\Component $component, array $validation): void
    {
        $rule = $validation['validation'];

        $field = $validation['field'] ?? null;

        $value = $validation['value'] ?? null;

        if (method_exists($component, $rule)) {
            if ($field) {
                $component->{$rule}($field, $value);
            } else {
                if ($value) {
                    $component->{$rule}($value);
                } else {
                    $component->{$rule}();
                }
            }
        }
    }

    protected function applySetting(Forms\Components\Component $component, array $setting): void
    {
        $name = $setting['setting'];
        $value = $setting['value'] ?? null;

        if (method_exists($component, $name)) {
            if ($value !== null) {
                $component->{$name}($value);
            } else {
                $component->{$name}();
            }
        }
    }
}
