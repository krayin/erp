<?php

namespace Webkul\Fields\Filament\Resources;

use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Webkul\Fields\FieldsColumnManager;
use Webkul\Fields\Filament\Resources\FieldResource\Pages;
use Webkul\Fields\Models\Field;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('field::app.model-label');
    }

    public static function getNavigationLabel(): string
    {
        return __('field::app.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('field::app.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('field::app.form.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->label(__('field::app.form.fields.code'))
                                    ->maxLength(255)
                                    ->disabledOn('edit'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make(__('field::app.form.sections.options'))
                            ->visible(fn(Forms\Get $get): bool => in_array($get('type'), [
                                'select',
                                'checkbox_list',
                                'radio',
                            ]))
                            ->schema([
                                Forms\Components\Repeater::make('options')
                                    ->hiddenLabel()
                                    ->simple(
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                    )
                                    ->addActionLabel(__('field::app.form.actions.add-option')),
                            ]),

                        Forms\Components\Section::make(__('field::app.form.sections.form-settings'))
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema(static::getFormSettingsSchema())
                                    ->statePath('form_settings'),
                            ]),

                        Forms\Components\Section::make(__('field::app.form.sections.table-settings'))
                            ->schema(static::getTableSettingsSchema()),

                        Forms\Components\Section::make(__('field::app.form.sections.infolist-settings'))
                            ->schema(static::getInfolistSettingsSchema()),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('field::app.form.sections.settings'))
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label(__('field::app.form.fields.type'))
                                    ->required()
                                    ->disabledOn('edit')
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->options([
                                        'text'          => __('field::app.form.fields.types.text'),
                                        'textarea'      => __('field::app.form.fields.types.textarea'),
                                        'select'        => __('field::app.form.fields.types.select'),
                                        'checkbox'      => __('field::app.form.fields.types.checkbox'),
                                        'radio'         => __('field::app.form.fields.types.radio'),
                                        'toggle'        => __('field::app.form.fields.types.toggle'),
                                        'checkbox_list' => __('field::app.form.fields.types.checkbox-list'),
                                        'datetime'      => __('field::app.form.fields.types.datetime'),
                                        'editor'        => __('field::app.form.fields.types.editor'),
                                        'markdown'      => __('field::app.form.fields.types.markdown'),
                                        'color'         => __('field::app.form.fields.types.color'),
                                    ]),
                                Forms\Components\Select::make('input_type')
                                    ->label(__('field::app.form.fields.field-input-types'))
                                    ->required()
                                    ->disabledOn('edit')
                                    ->native(false)
                                    ->visible(fn(Forms\Get $get): bool => $get('type') == 'text')
                                    ->options([
                                        'text'     => __('field::app.form.fields.input-types.text'),
                                        'email'    => __('field::app.form.fields.input-types.email'),
                                        'numeric'  => __('field::app.form.fields.input-types.numeric'),
                                        'integer'  => __('field::app.form.fields.input-types.integer'),
                                        'password' => __('field::app.form.fields.input-types.password'),
                                        'tel'      => __('field::app.form.fields.input-types.tel'),
                                        'url'      => __('field::app.form.fields.input-types.url'),
                                        'color'    => __('field::app.form.fields.input-types.color'),
                                    ]),
                                Forms\Components\Toggle::make('is_multiselect')
                                    ->label(__('field::app.form.fields.is-multiselect'))
                                    ->required()
                                    ->visible(fn(Forms\Get $get): bool => $get('type') == 'select')
                                    ->live(),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label(__('field::app.form.fields.sort-order'))
                                    ->required()
                                    ->integer()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Section::make(__('field::app.form.fields.resource'))
                            ->schema([
                                Forms\Components\Select::make('customizable_type')
                                    ->label(__('field::app.form.fields.resource'))
                                    ->required()
                                    ->searchable()
                                    ->native(false)
                                    ->disabledOn('edit')
                                    ->options(fn() => collect(Filament::getResources())->filter(fn($resource) => in_array('Webkul\Fields\Filament\Traits\HasCustomFields', class_uses($resource)))->mapWithKeys(fn($resource) => [
                                        $resource::getModel() => str($resource)->afterLast('\\')->toString(),
                                    ])),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('field::app.table.columns.code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('field::app.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('field::app.table.columns.type')),
                Tables\Columns\TextColumn::make('customizable_type')
                    ->label(__('field::app.table.columns.resource'))
                    ->description(fn(Field $record): string => str($record->customizable_type)->afterLast('\\')->toString() . __('field::app.form.fields.resource')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('field::app.table.columns.created-at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('field::app.table.filters.type.label'))
                    ->options([
                        'text'          => __('field::app.form.fields.types.text'),
                        'textarea'      => __('field::app.form.fields.types.textarea'),
                        'select'        => __('field::app.form.fields.types.select'),
                        'checkbox'      => __('field::app.form.fields.types.checkbox'),
                        'radio'         => __('field::app.form.fields.types.radio'),
                        'toggle'        => __('field::app.form.fields.types.toggle'),
                        'checkbox_list' => __('field::app.form.fields.types.checkbox-list'),
                        'datetime'      => __('field::app.form.fields.types.datetime'),
                        'editor'        => __('field::app.form.fields.types.editor'),
                        'markdown'      => __('field::app.form.fields.types.markdown'),
                        'color'         => __('field::app.form.fields.types.color'),
                    ]),
                Tables\Filters\SelectFilter::make('customizable_type')
                    ->label(__('field::app.table.filters.resource.label'))
                    ->options(fn() => collect(Filament::getResources())->filter(fn($resource) => in_array('Webkul\Fields\Filament\Traits\HasCustomFields', class_uses($resource)))->mapWithKeys(fn($resource) => [
                        $resource::getModel() => str($resource)->afterLast('\\')->toString(),
                    ])),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn($record) => $record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make()
                        ->before(function ($record) {
                            FieldsColumnManager::deleteColumn($record);
                        }),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->before(function ($records) {
                            foreach ($records as $record) {
                                FieldsColumnManager::deleteColumn($record);
                            }
                        }),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit'   => Pages\EditField::route('/{record}/edit'),
        ];
    }

    public static function getFormSettingsSchema(): array
    {
        return [
            Forms\Components\Fieldset::make(__('field::app.form.sections.validations'))
                ->schema([
                    Forms\Components\Repeater::make('validations')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Select::make('validation')
                                ->label(__('field::app.form.fields.validation'))
                                ->searchable()
                                ->required()
                                ->distinct()
                                ->live()
                                ->options(fn(Forms\Get $get): array => static::getTypeFormValidations($get('../../../type'))),
                            Forms\Components\TextInput::make('field')
                                ->label(__('field::app.form.fields.field'))
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => in_array($get('validation'), [
                                    'prohibitedIf',
                                    'prohibitedUnless',
                                    'requiredIf',
                                    'requiredUnless',
                                ])),
                            Forms\Components\TextInput::make('value')
                                ->label(__('field::app.form.fields.value'))
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => in_array($get('validation'), [
                                    'after',
                                    'afterOrEqual',
                                    'before',
                                    'beforeOrEqual',
                                    'different',
                                    'doesntEndWith',
                                    'doesntStartWith',
                                    'endsWith',
                                    'gt',
                                    'gte',
                                    'in',
                                    'lt',
                                    'lte',
                                    'maxSize',
                                    'minSize',
                                    'multipleOf',
                                    'notIn',
                                    'notRegex',
                                    'prohibitedIf',
                                    'prohibitedUnless',
                                    'prohibits',
                                    'regex',
                                    'requiredIf',
                                    'requiredUnless',
                                    'requiredWith',
                                    'requiredWithAll',
                                    'requiredWithout',
                                    'requiredWithoutAll',
                                    'rules',
                                    'same',
                                    'startsWith',
                                ])),
                        ])
                        ->addActionLabel(__('field::app.form.actions.add-option'))
                        ->columns(3)
                        ->collapsible()
                        ->itemLabel(function (array $state, Forms\Get $get): ?string {
                            $validations = static::getTypeFormValidations($get('../type'));

                            return $validations[$state['validation']] ?? null;
                        }),
                ])
                ->columns(1),

            Forms\Components\Fieldset::make(__('field::app.form.sections.additional-settings'))
                ->schema([
                    Forms\Components\Repeater::make('settings')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Select::make('setting')
                                ->label(__('field::app.form.fields.setting'))
                                ->required()
                                ->distinct()
                                ->searchable()
                                ->live()
                                ->options(fn(Forms\Get $get): array => static::getTypeFormSettings($get('../../../type'))),
                            Forms\Components\TextInput::make('value')
                                ->label(__('field::app.form.fields.value'))
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                                    'autocapitalize',
                                    'autocomplete',
                                    'default',
                                    'disabledDates',
                                    'displayFormat',
                                    'format',
                                    'helperText',
                                    'hint',
                                    'hintIcon',
                                    'id',
                                    'loadingMessage',
                                    'locale',
                                    'mask',
                                    'noSearchResultsMessage',
                                    'offIcon',
                                    'onIcon',
                                    'placeholder',
                                    'prefix',
                                    'prefixIcon',
                                    'searchingMessage',
                                    'searchPrompt',
                                    'suffix',
                                    'suffixIcon',
                                    'timezone',
                                ])),
                            Forms\Components\TextInput::make('value')
                                ->label(__('field::app.form.fields.value'))
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                                    'cols',
                                    'columns',
                                    'firstDayOfWeek',
                                    'hoursStep',
                                    'maxItems',
                                    'minItems',
                                    'minutesStep',
                                    'optionsLimit',
                                    'rows',
                                    'searchDebounce',
                                    'seconds',
                                    'secondsStep',
                                    'step',
                                ])),
                            Forms\Components\Select::make('value')
                                ->label(__('field::app.form.fields.color'))
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                                    'hintColor',
                                    'prefixIconColor',
                                    'suffixIconColor',
                                    'onColor',
                                    'offColor',
                                ]))
                                ->options([
                                    'danger'    => __('field::app.form.fields.types.danger'),
                                    'info'      => __('field::app.form.fields.types.info'),
                                    'primary'   => __('field::app.form.fields.types.primary'),
                                    'secondary' => __('field::app.form.fields.types.secondary'),
                                    'warning'   => __('field::app.form.fields.types.warning'),
                                    'success'   => __('field::app.form.fields.types.success'),
                                ]),
                            Forms\Components\Select::make('value')
                                ->label(__('field::app.form.fields.value'))
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                                    'gridDirection',
                                ]))
                                ->options([
                                    'row'    => __('field::app.form.fields.row'),
                                    'column' => __('field::app.form.fields.column'),
                                ]),
                            Forms\Components\Select::make('value')
                                ->label(__('field::app.form.fields.value'))
                                ->required()
                                ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                                    'inputMode',
                                ]))
                                ->options([
                                    'none'    => __('field::app.form.fields.input-types.none'),
                                    'text'    => __('field::app.form.fields.input-types.text'),
                                    'numeric' => __('field::app.form.fields.input-types.numeric'),
                                    'decimal' => __('field::app.form.fields.input-types.decimal'),
                                    'tel'     => __('field::app.form.fields.input-types.tel'),
                                    'search'  => __('field::app.form.fields.input-types.search'),
                                    'email'   => __('field::app.form.fields.input-types.email'),
                                    'url'     => __('field::app.form.fields.input-types.url'),
                                ]),
                        ])
                        ->addActionLabel(__('field::app.form.actions.add-setting'))
                        ->columns(2)
                        ->collapsible()
                        ->itemLabel(function (array $state, Forms\Get $get): ?string {
                            $settings = static::getTypeFormSettings($get('../type'));

                            return $settings[$state['setting']] ?? null;
                        }),
                ])
                ->columns(1),
        ];
    }

    public static function getTypeFormValidations(?string $type): array
    {
        if (is_null($type)) {
            return [];
        }

        $commonValidations = [
            'gt'                 => __('field::app.form.validations.common.gt'),
            'gte'                => __('field::app.form.validations.common.gte'),
            'lt'                 => __('field::app.form.validations.common.lt'),
            'lte'                => __('field::app.form.validations.common.lte'),
            'maxSize'            => __('field::app.form.validations.common.max-size'),
            'minSize'            => __('field::app.form.validations.common.min-size'),
            'multipleOf'         => __('field::app.form.validations.common.multiple-of'),
            'nullable'           => __('field::app.form.validations.common.nullable'),
            'prohibited'         => __('field::app.form.validations.common.prohibited'),
            'prohibitedIf'       => __('field::app.form.validations.common.prohibited-if'),
            'prohibitedUnless'   => __('field::app.form.validations.common.prohibited-unless'),
            'prohibits'          => __('field::app.form.validations.common.prohibits'),
            'required'           => __('field::app.form.validations.common.required'),
            'requiredIf'         => __('field::app.form.validations.common.required-if'),
            'requiredIfAccepted' => __('field::app.form.validations.common.required-if-accepted'),
            'requiredUnless'     => __('field::app.form.validations.common.required-unless'),
            'requiredWith'       => __('field::app.form.validations.common.required-with'),
            'requiredWithAll'    => __('field::app.form.validations.common.required-with-all'),
            'requiredWithout'    => __('field::app.form.validations.common.required-without'),
            'requiredWithoutAll' => __('field::app.form.validations.common.required-without-all'),
            'rules'              => __('field::app.form.validations.common.rules'),
            'unique'             => __('field::app.form.validations.common.unique'),
        ];

        $typeValidations = match ($type) {
            'text' => [
                'alphaDash'       => __('field::app.form.validations.text.alpha-dash'),
                'alphaNum'        => __('field::app.form.validations.text.alpha-num'),
                'ascii'           => __('field::app.form.validations.text.ascii'),
                'doesntEndWith'   => __('field::app.form.validations.text.doesnt-end-with'),
                'doesntStartWith' => __('field::app.form.validations.text.doesnt-start-with'),
                'endsWith'        => __('field::app.form.validations.text.ends-with'),
                'filled'          => __('field::app.form.validations.text.filled'),
                'ip'              => __('field::app.form.validations.text.ip'),
                'ipv4'            => __('field::app.form.validations.text.ipv4'),
                'ipv6'            => __('field::app.form.validations.text.ipv6'),
                'length'          => __('field::app.form.validations.text.length'),
                'macAddress'      => __('field::app.form.validations.text.mac-address'),
                'maxLength'       => __('field::app.form.validations.text.max-length'),
                'minLength'       => __('field::app.form.validations.text.min-length'),
                'regex'           => __('field::app.form.validations.text.regex'),
                'startsWith'      => __('field::app.form.validations.text.starts-with'),
                'ulid'            => __('field::app.form.validations.text.ulid'),
                'uuid'            => __('field::app.form.validations.text.uuid'),
            ],

            'textarea' => [
                'filled'    => __('field::app.form.validations.textarea.filled'),
                'maxLength' => __('field::app.form.validations.textarea.max-length'),
                'minLength' => __('field::app.form.validations.textarea.min-length'),
            ],

            'select' => [
                'different' => __('field::app.form.validations.select.different'),
                'exists'    => __('field::app.form.validations.select.exists'),
                'in'        => __('field::app.form.validations.select.in'),
                'notIn'     => __('field::app.form.validations.select.not-in'),
                'same'      => __('field::app.form.validations.select.same'),
            ],

            'radio' => [],

            'checkbox' => [
                'accepted' => __('field::app.form.validations.checkbox.accepted'),
                'declined' => __('field::app.form.validations.checkbox.declined'),
            ],

            'toggle' => [
                'accepted' => __('field::app.form.validations.toggle.accepted'),
                'declined' => __('field::app.form.validations.toggle.declined'),
            ],

            'checkbox_list' => [
                'in'       => __('field::app.form.validations.checkbox-list.in'),
                'maxItems' => __('field::app.form.validations.checkbox-list.max-items'),
                'minItems' => __('field::app.form.validations.checkbox-list.min-items'),
            ],

            'datetime' => [
                'after'         => __('field::app.form.validations.datetime.after'),
                'afterOrEqual'  => __('field::app.form.validations.datetime.after-or-equal'),
                'before'        => __('field::app.form.validations.datetime.before'),
                'beforeOrEqual' => __('field::app.form.validations.datetime.before-or-equal'),
            ],

            'editor' => [
                'filled'    => __('field::app.form.validations.editor.filled'),
                'maxLength' => __('field::app.form.validations.editor.max-length'),
                'minLength' => __('field::app.form.validations.editor.min-length'),
            ],

            'markdown' => [
                'filled'    => __('field::app.form.validations.markdown.filled'),
                'maxLength' => __('field::app.form.validations.markdown.max-length'),
                'minLength' => __('field::app.form.validations.markdown.min-length'),
            ],

            'color' => [
                'hexColor' => __('field::app.form.validations.color.hex-color'),
            ],

            default => [],
        };

        return array_merge($typeValidations, $commonValidations);
    }

    public static function getTypeFormSettings(?string $type): array
    {
        if (is_null($type)) {
            return [];
        }

        return match ($type) {
            'text' => [
                'autocapitalize'  => __('field::app.form.settings.text.autocapitalize'),
                'autocomplete'    => __('field::app.form.settings.text.autocomplete'),
                'autofocus'       => __('field::app.form.settings.text.autofocus'),
                'default'         => __('field::app.form.settings.text.default'),
                'disabled'        => __('field::app.form.settings.text.disabled'),
                'helperText'      => __('field::app.form.settings.text.helper-text'),
                'hint'            => __('field::app.form.settings.text.hint'),
                'hintColor'       => __('field::app.form.settings.text.hint-color'),
                'hintIcon'        => __('field::app.form.settings.text.hint-icon'),
                'id'              => __('field::app.form.settings.text.id'),
                'inputMode'       => __('field::app.form.settings.text.input-mode'),
                'mask'            => __('field::app.form.settings.text.mask'),
                'placeholder'     => __('field::app.form.settings.text.placeholder'),
                'prefix'          => __('field::app.form.settings.text.prefix'),
                'prefixIcon'      => __('field::app.form.settings.text.prefix-icon'),
                'prefixIconColor' => __('field::app.form.settings.text.prefix-icon-color'),
                'readOnly'        => __('field::app.form.settings.text.read-only'),
                'step'            => __('field::app.form.settings.text.step'),
                'suffix'          => __('field::app.form.settings.text.suffix'),
                'suffixIcon'      => __('field::app.form.settings.text.suffix-icon'),
                'suffixIconColor' => __('field::app.form.settings.text.suffix-icon-color'),
            ],

            'textarea' => [
                'autofocus'   => __('field::app.form.settings.textarea.autofocus'),
                'autosize'    => __('field::app.form.settings.textarea.autosize'),
                'cols'        => __('field::app.form.settings.textarea.cols'),
                'default'     => __('field::app.form.settings.textarea.default'),
                'disabled'    => __('field::app.form.settings.textarea.disabled'),
                'helperText'  => __('field::app.form.settings.textarea.helper-text'),
                'hint'        => __('field::app.form.settings.textarea.hint'),
                'hintColor'   => __('field::app.form.settings.textarea.hint-color'),
                'hintIcon'    => __('field::app.form.settings.textarea.hinticon'),
                'id'          => __('field::app.form.settings.textarea.id'),
                'placeholder' => __('field::app.form.settings.textarea.placeholder'),
                'readOnly'    => __('field::app.form.settings.textarea.read-only'),
                'rows'        => __('field::app.form.settings.textarea.rows'),
            ],

            'select' => [
                'default'                => __('field::app.form.settings.select.default'),
                'disabled'               => __('field::app.form.settings.select.disabled'),
                'helperText'             => __('field::app.form.settings.select.helper-text'),
                'hint'                   => __('field::app.form.settings.select.hint'),
                'hintColor'              => __('field::app.form.settings.select.hint-color'),
                'hintIcon'               => __('field::app.form.settings.select.hint-icon'),
                'id'                     => __('field::app.form.settings.select.id'),
                'loadingMessage'         => __('field::app.form.settings.select.loading-message'),
                'noSearchResultsMessage' => __('field::app.form.settings.select.no-search-results-message'),
                'optionsLimit'           => __('field::app.form.settings.select.options-limit'),
                'preload'                => __('field::app.form.settings.select.preload'),
                'searchable'             => __('field::app.form.settings.select.searchable'),
                'searchDebounce'         => __('field::app.form.settings.select.search-debounce'),
                'searchingMessage'       => __('field::app.form.settings.select.searching-message'),
                'searchPrompt'           => __('field::app.form.settings.select.search-prompt'),
            ],

            'radio' => [
                'default'    => __('field::app.form.settings.radio.default'),
                'disabled'   => __('field::app.form.settings.radio.disabled'),
                'helperText' => __('field::app.form.settings.radio.helper-text'),
                'hint'       => __('field::app.form.settings.radio.hint'),
                'hintColor'  => __('field::app.form.settings.radio.hint-color'),
                'hintIcon'   => __('field::app.form.settings.radio.hint-icon'),
                'id'         => __('field::app.form.settings.radio.id'),
            ],

            'checkbox' => [
                'default'    => __('field::app.form.settings.checkbox.default'),
                'disabled'   => __('field::app.form.settings.checkbox.disabled'),
                'helperText' => __('field::app.form.settings.checkbox.helper-text'),
                'hint'       => __('field::app.form.settings.checkbox.hint'),
                'hintColor'  => __('field::app.form.settings.checkbox.hint-color'),
                'hintIcon'   => __('field::app.form.settings.checkbox.hint-icon'),
                'id'         => __('field::app.form.settings.checkbox.id'),
                'inline'     => __('field::app.form.settings.checkbox.inline'),
            ],

            'toggle' => [
                'default'    => __('field::app.form.settings.toggle.default'),
                'disabled'   => __('field::app.form.settings.toggle.disabled'),
                'helperText' => __('field::app.form.settings.toggle.helper-text'),
                'hint'       => __('field::app.form.settings.toggle.hint'),
                'hintColor'  => __('field::app.form.settings.toggle.hint-color'),
                'hintIcon'   => __('field::app.form.settings.toggle.hint-icon'),
                'id'         => __('field::app.form.settings.toggle.id'),
                'offColor'   => __('field::app.form.settings.toggle.off-color'),
                'offIcon'    => __('field::app.form.settings.toggle.off-icon'),
                'onColor'    => __('field::app.form.settings.toggle.on-color'),
                'onIcon'     => __('field::app.form.settings.toggle.on-icon'),
            ],

            'checkbox-list' => [
                'bulkToggleable'         => __('field::app.form.settings.checkbox-list.bulk-toggleable'),
                'columns'                => __('field::app.form.settings.checkbox-list.columns'),
                'default'                => __('field::app.form.settings.checkbox-list.default'),
                'disabled'               => __('field::app.form.settings.checkbox-list.disabled'),
                'gridDirection'          => __('field::app.form.settings.checkbox-list.grid-direction'),
                'helperText'             => __('field::app.form.settings.checkbox-list.helper-text'),
                'hint'                   => __('field::app.form.settings.checkbox-list.hint'),
                'hintColor'              => __('field::app.form.settings.checkbox-list.hint-color'),
                'hintIcon'               => __('field::app.form.settings.checkbox-list.hint-icon'),
                'id'                     => __('field::app.form.settings.checkbox-list.id'),
                'maxItems'               => __('field::app.form.settings.checkbox-list.max-items'),
                'minItems'               => __('field::app.form.settings.checkbox-list.min-items'),
                'noSearchResultsMessage' => __('field::app.form.settings.checkbox-list.no-search-results-message'),
                'searchable'             => __('field::app.form.settings.checkbox-list.searchable'),
            ],

            'datetime' => [
                'closeOnDateSelection'   => __('field::app.form.settings.datetime.close-on-date-selection'),
                'default'                => __('field::app.form.settings.datetime.default'),
                'disabled'               => __('field::app.form.settings.datetime.disabled'),
                'disabledDates'          => __('field::app.form.settings.datetime.disabled-dates'),
                'displayFormat'          => __('field::app.form.settings.datetime.display-format'),
                'firstDayOfWeek'         => __('field::app.form.settings.datetime.first-day-of-week'),
                'format'                 => __('field::app.form.settings.datetime.format'),
                'helperText'             => __('field::app.form.settings.datetime.helper-text'),
                'hint'                   => __('field::app.form.settings.datetime.hint'),
                'hintColor'              => __('field::app.form.settings.datetime.hint-color'),
                'hintIcon'               => __('field::app.form.settings.datetime.hint-icon'),
                'hoursStep'              => __('field::app.form.settings.datetime.hours-step'),
                'id'                     => __('field::app.form.settings.datetime.id'),
                'locale'                 => __('field::app.form.settings.datetime.locale'),
                'minutesStep'            => __('field::app.form.settings.datetime.minutes-step'),
                'seconds'                => __('field::app.form.settings.datetime.seconds'),
                'secondsStep'            => __('field::app.form.settings.datetime.seconds-step'),
                'timezone'               => __('field::app.form.settings.datetime.timezone'),
                'weekStartsOnMonday'     => __('field::app.form.settings.datetime.week-starts-on-monday'),
                'weekStartsOnSunday'     => __('field::app.form.settings.datetime.week-starts-on-sunday'),
            ],

            'editor' => [
                'default'     => __('field::app.form.settings.editor.default'),
                'disabled'    => __('field::app.form.settings.editor.disabled'),
                'helperText'  => __('field::app.form.settings.editor.helper-text'),
                'hint'        => __('field::app.form.settings.editor.hint'),
                'hintColor'   => __('field::app.form.settings.editor.hint-color'),
                'hintIcon'    => __('field::app.form.settings.editor.hint-icon'),
                'id'          => __('field::app.form.settings.editor.id'),
                'placeholder' => __('field::app.form.settings.editor.placeholder'),
                'readOnly'    => __('field::app.form.settings.editor.read-only'),
            ],

            'markdown' => [
                'default'     => __('field::app.form.settings.markdown.default'),
                'disabled'    => __('field::app.form.settings.markdown.disabled'),
                'helperText'  => __('field::app.form.settings.markdown.helper-text'),
                'hint'        => __('field::app.form.settings.markdown.hint'),
                'hintColor'   => __('field::app.form.settings.markdown.hint-color'),
                'hintIcon'    => __('field::app.form.settings.markdown.hint-icon'),
                'id'          => __('field::app.form.settings.markdown.id'),
                'placeholder' => __('field::app.form.settings.markdown.placeholder'),
                'readOnly'    => __('field::app.form.settings.markdown.read-only'),
            ],

            'color' => [
                'default'    => __('field::app.form.settings.color.default'),
                'disabled'   => __('field::app.form.settings.color.disabled'),
                'helperText' => __('field::app.form.settings.color.helper-text'),
                'hint'       => __('field::app.form.settings.color.hint'),
                'hintColor'  => __('field::app.form.settings.color.hint-color'),
                'hintIcon'   => __('field::app.form.settings.color.hint-icon'),
                'hsl'        => __('field::app.form.settings.color.hsl'),
                'id'         => __('field::app.form.settings.color.id'),
                'rgb'        => __('field::app.form.settings.color.rgb'),
                'rgba'       => __('field::app.form.settings.color.rgba'),
            ],

            'file' => [
                'acceptedFileTypes'                => __('field::app.form.settings.file.accepted-file-types'),
                'appendFiles'                      => __('field::app.form.settings.file.append-files'),
                'deletable'                        => __('field::app.form.settings.file.deletable'),
                'directory'                        => __('field::app.form.settings.file.directory'),
                'downloadable'                     => __('field::app.form.settings.file.downloadable'),
                'fetchFileInformation'             => __('field::app.form.settings.file.fetch-file-information'),
                'fileAttachmentsDirectory'         => __('field::app.form.settings.file.file-attachment-directory'),
                'fileAttachmentsVisibility'        => __('field::app.form.settings.file.file-attachments-visibility'),
                'image'                            => __('field::app.form.settings.file.image'),
                'imageCropAspectRatio'             => __('field::app.form.settings.file.image-crop-aspect-ratio'),
                'imageEditor'                      => __('field::app.form.settings.file.image-editor'),
                'imageEditorAspectRatios'          => __('field::app.form.settings.file.image-editor-aspect-ratios'),
                'imageEditorEmptyFillColor'        => __('field::app.form.settings.file.image-editor-empty-fill-color'),
                'imageEditorMode'                  => __('field::app.form.settings.file.image-editor-mode'),
                'imagePreviewHeight'               => __('field::app.form.settings.file.image-preview-height'),
                'imageResizeMode'                  => __('field::app.form.settings.file.image-resize-mode'),
                'imageResizeTargetHeight'          => __('field::app.form.settings.file.image-resize-target-height'),
                'imageResizeTargetWidth'           => __('field::app.form.settings.file.image-resize-target-width'),
                'loadingIndicatorPosition'         => __('field::app.form.settings.file.loading-indicator-position'),
                'moveFiles'                        => __('field::app.form.settings.file.move-files'),
                'openable'                         => __('field::app.form.settings.file.openable'),
                'orientImagesFromExif'             => __('field::app.form.settings.file.orient-images-from-exif'),
                'panelAspectRatio'                 => __('field::app.form.settings.file.panel-aspect-ratio'),
                'panelLayout'                      => __('field::app.form.settings.file.panel-layout'),
                'previewable'                      => __('field::app.form.settings.file.previewable'),
                'removeUploadedFileButtonPosition' => __('field::app.form.settings.file.remove-uploaded-file-button-position'),
                'reorderable'                      => __('field::app.form.settings.file.reorderable'),
                'storeFiles'                       => __('field::app.form.settings.file.store-files'),
                'uploadButtonPosition'             => __('field::app.form.settings.file.upload-button-position'),
                'uploadingMessage'                 => __('field::app.form.settings.file.uploading-message'),
                'uploadProgressIndicatorPosition'  => __('field::app.form.settings.file.upload-progress-indicator-position'),
                'visibility'                       => __('field::app.form.settings.file.visibility'),
            ],
        };
    }

    public static function getTableSettingsSchema(): array
    {
        return [
            Forms\Components\Toggle::make('use_in_table')
                ->label(__('field::app.form.fields.use-in-table'))
                ->required()
                ->live(),
            Forms\Components\Repeater::make('table_settings')
                ->hiddenLabel()
                ->visible(fn(Forms\Get $get): bool => $get('use_in_table'))
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->label(__('field::app.form.fields.setting'))
                        ->searchable()
                        ->required()
                        ->distinct()
                        ->live()
                        ->options(fn(Forms\Get $get): array => static::getTypeTableSettings($get('../../type'))),
                    Forms\Components\TextInput::make('value')
                        ->label('Value')
                        ->label(__('field::app.form.fields.value'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'copyMessage',
                            'dateTimeTooltip',
                            'default',
                            'icon',
                            'label',
                            'money',
                            'placeholder',
                            'prefix',
                            'suffix',
                            'tooltip',
                            'width',
                        ])),

                    Forms\Components\Select::make('value')
                        ->label(__('field::app.form.fields.value'))
                        ->label('Color')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'color',
                            'iconColor',
                        ]))
                        ->options([
                            'danger'    => __('field::app.form.fields.types.danger'),
                            'info'      => __('field::app.form.fields.types.info'),
                            'primary'   => __('field::app.form.fields.types.primary'),
                            'secondary' => __('field::app.form.fields.types.secondary'),
                            'warning'   => __('field::app.form.fields.types.warning'),
                            'success'   => __('field::app.form.fields.types.success'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('field::app.form.fields.alignment'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'alignment',
                            'verticalAlignment',
                        ]))
                        ->options([
                            Alignment::Start->value   => __('field::app.form.fields.types.start'),
                            Alignment::Left->value    => __('field::app.form.fields.types.left'),
                            Alignment::Center->value  => __('field::app.form.fields.types.center'),
                            Alignment::End->value     => __('field::app.form.fields.types.end'),
                            Alignment::Right->value   => __('field::app.form.fields.types.right'),
                            Alignment::Justify->value => __('field::app.form.fields.types.justify'),
                            Alignment::Between->value => __('field::app.form.fields.types.between'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('field::app.form.fields.font-weight'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'weight',
                        ]))
                        ->options([
                            FontWeight::Thin->name       => __('field::app.form.fields.types.thin'),
                            FontWeight::ExtraLight->name => __('field::app.form.fields.types.extra-light'),
                            FontWeight::Light->name      => __('field::app.form.fields.types.light'),
                            FontWeight::Normal->name     => __('field::app.form.fields.types.normal'),
                            FontWeight::Medium->name     => __('field::app.form.fields.types.medium'),
                            FontWeight::SemiBold->name   => __('field::app.form.fields.types.semi-bold'),
                            FontWeight::Bold->name       => __('field::app.form.fields.types.bold'),
                            FontWeight::ExtraBold->name  => __('field::app.form.fields.types.extra-bold'),
                            FontWeight::Black->name      => __('field::app.form.fields.types.black'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('field::app.form.fields.icon-position'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'iconPosition',
                        ]))
                        ->options([
                            IconPosition::Before->value => __('field::app.form.fields.types.before'),
                            IconPosition::After->value  => __('field::app.form.fields.types.after'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('field::app.form.fields.size'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'size',
                        ]))
                        ->options([
                            TextColumn\TextColumnSize::Small->name  => __('field::app.form.fields.types.small'),
                            TextColumn\TextColumnSize::Medium->name => __('field::app.form.fields.types.medium'),
                            TextColumn\TextColumnSize::Large->name  => __('field::app.form.fields.types.large'),
                        ]),

                    Forms\Components\TextInput::make('value')
                        ->label(__('field::app.form.fields.value'))
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'limit',
                            'words',
                            'lineClamp',
                            'copyMessageDuration',
                        ])),
                ])
                ->addActionLabel(__('field::app.form.actions.add-setting'))
                ->columns(2)
                ->collapsible()
                ->itemLabel(function (array $state, Forms\Get $get): ?string {
                    $settings = static::getTypeTableSettings($get('type'));

                    return $settings[$state['setting']] ?? null;
                }),
        ];
    }

    public static function getTypeTableSettings(?string $type): array
    {
        if (is_null($type)) {
            return [];
        }

        $commonSettings = [
            'alignEnd'             => __('field::app.form.settings.common.align-end'),
            'alignment'            => __('field::app.form.settings.common.alignment'),
            'alignStart'           => __('field::app.form.settings.common.align-start'),
            'badge'                => __('field::app.form.settings.common.badge'),
            'boolean'              => __('field::app.form.settings.common.boolean'),
            'color'                => __('field::app.form.settings.common.color'),
            'copyable'             => __('field::app.form.settings.common.copyable'),
            'copyMessage'          => __('field::app.form.settings.common.copy-message'),
            'copyMessageDuration'  => __('field::app.form.settings.common.copy-message-duration'),
            'default'              => __('field::app.form.settings.common.default'),
            'filterable'           => __('field::app.form.settings.common.filterable'),
            'groupable'            => __('field::app.form.settings.common.groupable'),
            'grow'                 => __('field::app.form.settings.common.grow'),
            'icon'                 => __('field::app.form.settings.common.icon'),
            'iconColor'            => __('field::app.form.settings.common.icon-color'),
            'iconPosition'         => __('field::app.form.settings.common.icon-position'),
            'label'                => __('field::app.form.settings.common.label'),
            'limit'                => __('field::app.form.settings.common.limit'),
            'lineClamp'            => __('field::app.form.settings.common.line-clamp'),
            'money'                => __('field::app.form.settings.common.money'),
            'placeholder'          => __('field::app.form.settings.common.placeholder'),
            'prefix'               => __('field::app.form.settings.common.prefix'),
            'searchable'           => __('field::app.form.settings.common.searchable'),
            'size'                 => __('field::app.form.settings.common.size'),
            'sortable'             => __('field::app.form.settings.common.sortable'),
            'suffix'               => __('field::app.form.settings.common.suffix'),
            'toggleable'           => __('field::app.form.settings.common.toggleable'),
            'tooltip'              => __('field::app.form.settings.common.tooltip'),
            'verticalAlignment'    => __('field::app.form.settings.common.vertical-alignment'),
            'verticallyAlignStart' => __('field::app.form.settings.common.vertically-align-start'),
            'weight'               => __('field::app.form.settings.common.weight'),
            'width'                => __('field::app.form.settings.common.width'),
            'words'                => __('field::app.form.settings.common.words'),
            'wrapHeader'           => __('field::app.form.settings.common.wrap-header'),
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'date'            => __('field::app.form.settings.type.datetime.date'),
                'dateTime'        => __('field::app.form.settings.type.datetime.date-time'),
                'dateTimeTooltip' => __('field::app.form.settings.type.datetime.date-time-tooltip'),
                'since'           => __('field::app.form.settings.type.datetime.since'),
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }

    public static function getInfolistSettingsSchema(): array
    {
        return [
            Forms\Components\Repeater::make('infolist_settings')
                ->label(__('field::app.form.sections.infolist-settings'))
                ->hiddenLabel()
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->label(__('field::app.form.fields.setting'))
                        ->searchable()
                        ->required()
                        ->distinct()
                        ->live()
                        ->options(fn(Forms\Get $get): array => static::getTypeInfolistSettings($get('../../type'))),
                    Forms\Components\TextInput::make('value')
                        ->label(__('field::app.form.fields.value'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'copyMessage',
                            'dateTimeTooltip',
                            'default',
                            'icon',
                            'label',
                            'money',
                            'placeholder',
                            'tooltip',
                            'helperText',
                            'hint',
                            'hintIcon',
                            'separator',
                            'trueIcon',
                            'falseIcon',
                        ])),

                    Forms\Components\Select::make('value')
                        ->label(__('field::app.form.fields.color'))
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'color',
                            'iconColor',
                            'hintColor',
                            'trueColor',
                            'falseColor',
                        ]))
                        ->options([
                            'danger'    => __('field::app.form.fields.types.danger'),
                            'info'      => __('field::app.form.fields.types.info'),
                            'primary'   => __('field::app.form.fields.types.primary'),
                            'secondary' => __('field::app.form.fields.types.secondary'),
                            'warning'   => __('field::app.form.fields.types.warning'),
                            'success'   => __('field::app.form.fields.types.success'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Font Weight')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'weight',
                        ]))
                        ->options([
                            FontWeight::Thin->name       => __('field::app.form.fields.types.thin'),
                            FontWeight::ExtraLight->name => __('field::app.form.fields.types.extra-light'),
                            FontWeight::Light->name      => __('field::app.form.fields.types.light'),
                            FontWeight::Normal->name     => __('field::app.form.fields.types.normal'),
                            FontWeight::Medium->name     => __('field::app.form.fields.types.medium'),
                            FontWeight::SemiBold->name   => __('field::app.form.fields.types.semi-bold'),
                            FontWeight::Bold->name       => __('field::app.form.fields.types.bold'),
                            FontWeight::ExtraBold->name  => __('field::app.form.fields.types.extra-bold'),
                            FontWeight::Black->name      => __('field::app.form.fields.types.black'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Icon Position')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'iconPosition',
                        ]))
                        ->options([
                            IconPosition::Before->value => __('field::app.form.fields.types.before'),
                            IconPosition::After->value  => __('field::app.form.fields.types.after'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Size')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'size',
                        ]))
                        ->options([
                            TextColumn\TextColumnSize::Small->name  => __('field::app.form.fields.types.small'),
                            TextColumn\TextColumnSize::Medium->name => __('field::app.form.fields.types.medium'),
                            TextColumn\TextColumnSize::Large->name  => __('field::app.form.fields.types.large'),
                        ]),

                    Forms\Components\TextInput::make('value')
                        ->label(__('field::app.form.fields.value'))
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'limit',
                            'words',
                            'lineClamp',
                            'copyMessageDuration',
                            'columnSpan',
                            'limitList',
                        ])),
                ])
                ->addActionLabel(__('field::app.form.actions.add-setting'))
                ->columns(2)
                ->collapsible()
                ->itemLabel(function (array $state, Forms\Get $get): ?string {
                    $settings = static::getTypeInfolistSettings($get('type'));

                    return $settings[$state['setting']] ?? null;
                }),
        ];
    }

    public static function getTypeInfolistSettings(?string $type): array
    {
        if (is_null($type)) {
            return [];
        }

        $commonSettings = [
            'badge'               => __('field::app.form.settings.common.badge'),
            'color'               => __('field::app.form.settings.common.color'),
            'copyable'            => __('field::app.form.settings.common.copyable'),
            'copyMessage'         => __('field::app.form.settings.common.copy-message'),
            'copyMessageDuration' => __('field::app.form.settings.common.copy-message-duration'),
            'default'             => __('field::app.form.settings.common.default'),
            'icon'                => __('field::app.form.settings.common.icon'),
            'iconColor'           => __('field::app.form.settings.common.icon-color'),
            'iconPosition'        => __('field::app.form.settings.common.icon-position'),
            'label'               => __('field::app.form.settings.common.label'),
            'limit'               => __('field::app.form.settings.common.limit'),
            'lineClamp'           => __('field::app.form.settings.common.limit-clamp'),
            'money'               => __('field::app.form.settings.common.money'),
            'placeholder'         => __('field::app.form.settings.common.placeholder'),
            'size'                => __('field::app.form.settings.common.size'),
            'tooltip'             => __('field::app.form.settings.common.tooltip'),
            'weight'              => __('field::app.form.settings.common.weight'),
            'words'               => __('field::app.form.settings.common.words'),
            'columnSpan'          => __('field::app.form.settings.common.column-span'),
            'helperText'          => __('field::app.form.settings.common.helper-text'),
            'hint'                => __('field::app.form.settings.common.hint'),
            'hintColor'           => __('field::app.form.settings.common.hint-color'),
            'hintIcon'            => __('field::app.form.settings.common.hint-icon'),
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'date'            => __('field::app.form.settings.type.datetime.date'),
                'dateTime'        => __('field::app.form.settings.type.datetime.date-time'),
                'dateTimeTooltip' => __('field::app.form.settings.type.datetime.date-time-tooltip'),
                'since'           => __('field::app.form.settings.type.datetime.since'),
            ],

            'checkbox_list' => [
                'separator'             => __('field::app.form.settings.type.checkbox-list.separator'),
                'listWithLineBreaks'    => __('field::app.form.settings.type.checkbox-list.list-with-line-breaks'),
                'bulleted'              => __('field::app.form.settings.type.checkbox-list.bulleted'),
                'limitList'             => __('field::app.form.settings.type.checkbox-list.limit-list'),
                'expandableLimitedList' => __('field::app.form.settings.type.checkbox-list.expandable-limited-list'),
            ],

            'select' => [
                'separator'             => __('field::app.form.settings.type.select.separator'),
                'listWithLineBreaks'    => __('field::app.form.settings.type.select.list-with-line-breaks'),
                'bulleted'              => __('field::app.form.settings.type.select.bulleted'),
                'limitList'             => __('field::app.form.settings.type.select.limit-list'),
                'expandableLimitedList' => __('field::app.form.settings.type.select.expandable-limited-list'),
            ],

            'checkbox' => [
                'boolean'    => __('field::app.form.settings.type.checkbox.boolean'),
                'falseIcon'  => __('field::app.form.settings.type.checkbox.false-icon'),
                'trueIcon'   => __('field::app.form.settings.type.checkbox.true-icon'),
                'trueColor'  => __('field::app.form.settings.type.checkbox.true-color'),
                'falseColor' => __('field::app.form.settings.type.checkbox.false-color'),
            ],

            'toggle' => [
                'boolean'    => __('field::app.form.settings.type.toggle.boolean'),
                'falseIcon'  => __('field::app.form.settings.type.toggle.false-icon'),
                'trueIcon'   => __('field::app.form.settings.type.toggle.true-icon'),
                'trueColor'  => __('field::app.form.settings.type.toggle.true-color'),
                'falseColor' => __('field::app.form.settings.type.toggle.false-color'),
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }
}
