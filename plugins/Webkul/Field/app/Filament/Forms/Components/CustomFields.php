<?php

namespace Webkul\Field\Filament\Forms\Components;

use Filament\Forms\Components\Component;
use Webkul\Field\Models\Field;
use Filament\Forms;
use Illuminate\Support\Collection;

class CustomFields extends Component
{
    protected string $view = 'field::filament.components.custom-fields';
    
    protected ?array $include = null;
    
    protected ?array $exclude = null;
    
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
            
        if ($this->include) {
            $query->whereIn('code', $this->include);
        }
        
        if ($this->exclude) {
            $query->whereNotIn('code', $this->exclude);
        }
        
        return $query->orderBy('sort_order')->get();
    }
    
    protected function getResourceClass(): string
    {
        if (! $this->resourceClass) {
            $this->resourceClass = get_class($this->getRecord());
        }
        
        return $this->resourceClass;
    }
    
    protected function createField(Field $field): Forms\Components\Component
    {
        $componentClass = match ($field->type) {
            'text' => Forms\Components\TextInput::class,
            'textarea' => Forms\Components\Textarea::class,
            'select' => Forms\Components\Select::class,
            'checkbox' => Forms\Components\Checkbox::class,
            'radio' => Forms\Components\Radio::class,
            'toggle' => Forms\Components\Toggle::class,
            'checkbox_list' => Forms\Components\CheckboxList::class,
            'datetime' => Forms\Components\DateTimePicker::class,
            'editor' => Forms\Components\RichEditor::class,
            'markdown' => Forms\Components\MarkdownEditor::class,
            'color' => Forms\Components\ColorPicker::class,
            default => Forms\Components\TextInput::class,
        };
        
        $component = $componentClass::make($field->code)
            ->label($field->name);
        
        // Apply field validations
        if (! empty($field->form_settings['validations'])) {
            foreach ($field->form_settings['validations'] as $validation) {
                $this->applyValidation($component, $validation);
            }
        }
        
        // Apply field settings
        if (! empty($field->form_settings['settings'])) {
            foreach ($field->form_settings['settings'] as $setting) {
                $this->applySetting($component, $setting);
            }
        }
        
        // Handle select/radio/checkbox options
        if (in_array($field->type, ['select', 'radio', 'checkbox_list']) && ! empty($field->options)) {
            $options = collect($field->options)->toArray();
            $options = array_combine($options, $options);
            $component->options($options);
        }
        
        return $component;
    }
    
    protected function applyValidation(Forms\Components\Component $component, array $validation): void
    {
        $rule = $validation['validation'];
        $value = $validation['value'] ?? null;
        
        if (method_exists($component, $rule)) {
            if ($value) {
                $component->{$rule}($value);
            } else {
                $component->{$rule}();
            }
        }
    }
    
    protected function applySetting(Forms\Components\Component $component, array $setting): void
    {
        $name = $setting['setting'];
        $field = $setting['field'] ?? null;
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