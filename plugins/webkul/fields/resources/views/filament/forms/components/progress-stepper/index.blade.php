@php
    $gridDirection = $getGridDirection() ?? 'column';
    $hasInlineLabel = $hasInlineLabel();
    $id = $getId();
    $isDisabled = $isDisabled();
    $isInline = $isInline();
    $isMultiple = $isMultiple();
    $statePath = $getStatePath();
@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
>
    <x-slot
        name="label"
        @class([
            'sm:pt-1.5' => $hasInlineLabel,
        ])
    >
        {{ $getLabel() }}
    </x-slot>

    <x-filament::grid
        :default="$getColumns('default')"
        :sm="$getColumns('sm')"
        :md="$getColumns('md')"
        :lg="$getColumns('lg')"
        :xl="$getColumns('xl')"
        :two-xl="$getColumns('2xl')"
        :is-grid="! $isInline"
        :direction="$gridDirection"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($attributes)
                ->merge($getExtraAttributes(), escape: false)
                ->class([
                    'state-container justify-end',
                    'fi-fo-radio',
                    '-mt-3' => (! $isInline) && ($gridDirection === 'column'),
                    'flex flex-wrap' => $isInline,
                ])
        "
    >
        @foreach ($getOptions() as $value => $label)
            @php
                $inputId = "{$id}-{$value}";
                $shouldOptionBeDisabled = $isDisabled || $isOptionDisabled($value, $label);
            @endphp

            <div
                @class([
                    'state' => true,
                    'border-primary-500',
                    'break-inside-avoid pt-3' => (! $isInline) && ($gridDirection === 'column'),
                ])
            >
                <input
                    @disabled($shouldOptionBeDisabled)
                    id="{{ $inputId }}"
                    @if (! $isMultiple)
                        name="{{ $id }}"
                    @endif
                    type="{{ $isMultiple ? 'checkbox' : 'radio' }}"
                    value="{{ $value }}"
                    wire:loading.attr="disabled"
                    {{ $applyStateBindingModifiers('wire:model') }}="{{ $statePath }}"
                    {{ $getExtraInputAttributeBag()->class(['peer pointer-events-none absolute opacity-0']) }}
                />

                <x-filament::button
                    class="stage-button"
                    :color="$getColor($value)"
                    {{-- :disabled="$shouldOptionBeDisabled" --}}
                    :for="$inputId"
                    :icon="$getIcon($value)"
                    tag="label"
                >
                    {{ $label }}
                </x-filament::button>
            </div>
        @endforeach
    </x-filament::grid>
</x-dynamic-component>

<style>
    .stage-button {
        border-radius: 0;
        padding-left: 30px;
        padding-right: 20px;
        border: 1px solid rgba(var(--gray-950), 0.2);
        box-shadow: none;
    }

    .dark .stage-button {
        border: 1px solid hsla(0, 0%, 100%, .2);
    }

    .stage-button:after {
        content: "";
        position: absolute;
        top: 50%;
        right: -14px;
        width: 26px;
        height: 26px;
        z-index: 1;
        transform: translateY(-50%) rotate(45deg);
        background-color: #ffffff;
        border-right: 1px solid rgba(var(--gray-950), 0.3);
        border-top: 1px solid rgba(var(--gray-950), 0.3);
        transition-duration: 75ms;
    }

    .dark .stage-button:after {
        background-color: rgba(var(--gray-900),var(--tw-bg-opacity));
        border-right: 1px solid hsla(0, 0%, 100%, .2);
        border-top: 1px solid hsla(0, 0%, 100%, .2);
    }

    .dark .stage-button:hover:after {
        background-color: rgba(var(--gray-800),var(--tw-bg-opacity));
    }

    .state-container .state:last-child .stage-button {
        border-radius: 0 8px 8px 0;
    }

    .state-container .state:first-child .stage-button {
        border-radius: 8px 0 0 8px;
    }

    .state-container .state:last-child .stage-button:after {
        content: none;
    }

    input:checked + .stage-button {
        color: #fff;
        border: 1px solid rgba(var(--c-500), var(--tw-bg-opacity));
    }

    input:checked + .stage-button:after {
        background-color: rgba(var(--c-600), var(--tw-bg-opacity));
        border-right: 1px solid rgba(var(--c-500));
        border-top: 1px solid rgba(var(--c-500));
    }

    .dark input:checked + .stage-button:after {
        background-color: rgba(var(--c-500), var(--tw-bg-opacity));
    }

    input:checked + .stage-button:hover:after {
        background-color: rgba(var(--c-500), var(--tw-bg-opacity));
        transition-duration: 75ms;
    }

    .dark input:checked + .stage-button:hover:after {
        background-color: rgba(var(--c-400), var(--tw-bg-opacity));
    }
</style>