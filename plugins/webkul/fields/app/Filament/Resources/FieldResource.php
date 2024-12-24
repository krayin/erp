<?php

namespace Webkul\Field\Filament\Resources;

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
use Illuminate\Support\Facades\Schema;
use Webkul\Field\FieldsColumnManager;
use Webkul\Field\Filament\Resources\FieldResource\Pages;
use Webkul\Field\Models\Field;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    public static function getModelLabel(): string
    {
        return __('fields::app.model-label');
    }

    public static function getNavigationLabel(): string
    {
        return __('fields::app.navigation.label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('fields::app.navigation.group');
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
                                    ->label(__('fields::app.form.fields.name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->label(__('fields::app.form.fields.code'))
                                    ->maxLength(255)
                                    ->disabledOn('edit')
                                    ->helperText(__('fields::app.form.fields.code-helper-text'))
                                    ->unique(ignoreRecord: true)
                                    ->notIn(function (Forms\Get $get) {
                                        if ($get('id')) {
                                            return [];
                                        }

                                        $table = app($get('customizable_type'))->getTable();

                                        return Schema::getColumnListing($table);
                                    })
                                    ->rules([
                                        'regex:/^[a-zA-Z_][a-zA-Z0-9_]*$/',
                                    ]),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make(__('fields::app.form.sections.options'))
                            ->visible(fn (Forms\Get $get): bool => in_array($get('type'), [
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
                                    ->addActionLabel(__('fields::app.form.actions.add-option')),
                            ]),

                        Forms\Components\Section::make(__('fields::app.form.sections.form-settings'))
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema(static::getFormSettingsSchema())
                                    ->statePath('form_settings'),
                            ]),

                        Forms\Components\Section::make(__('fields::app.form.sections.table-settings'))
                            ->schema(static::getTableSettingsSchema()),

                        Forms\Components\Section::make(__('fields::app.form.sections.infolist-settings'))
                            ->schema(static::getInfolistSettingsSchema()),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('fields::app.form.sections.settings'))
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label(__('fields::app.form.fields.type'))
                                    ->required()
                                    ->disabledOn('edit')
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->options([
                                        'text'          => __('fields::app.form.fields.types.text'),
                                        'textarea'      => __('fields::app.form.fields.types.textarea'),
                                        'select'        => __('fields::app.form.fields.types.select'),
                                        'checkbox'      => __('fields::app.form.fields.types.checkbox'),
                                        'radio'         => __('fields::app.form.fields.types.radio'),
                                        'toggle'        => __('fields::app.form.fields.types.toggle'),
                                        'checkbox_list' => __('fields::app.form.fields.types.checkbox-list'),
                                        'datetime'      => __('fields::app.form.fields.types.datetime'),
                                        'editor'        => __('fields::app.form.fields.types.editor'),
                                        'markdown'      => __('fields::app.form.fields.types.markdown'),
                                        'color'         => __('fields::app.form.fields.types.color'),
                                    ]),
                                Forms\Components\Select::make('input_type')
                                    ->label(__('fields::app.form.fields.field-input-types'))
                                    ->required()
                                    ->disabledOn('edit')
                                    ->native(false)
                                    ->visible(fn (Forms\Get $get): bool => $get('type') == 'text')
                                    ->options([
                                        'text'     => __('fields::app.form.fields.input-types.text'),
                                        'email'    => __('fields::app.form.fields.input-types.email'),
                                        'numeric'  => __('fields::app.form.fields.input-types.numeric'),
                                        'integer'  => __('fields::app.form.fields.input-types.integer'),
                                        'password' => __('fields::app.form.fields.input-types.password'),
                                        'tel'      => __('fields::app.form.fields.input-types.tel'),
                                        'url'      => __('fields::app.form.fields.input-types.url'),
                                        'color'    => __('fields::app.form.fields.input-types.color'),
                                    ]),
                                Forms\Components\Toggle::make('is_multiselect')
                                    ->label(__('fields::app.form.fields.is-multiselect'))
                                    ->required()
                                    ->visible(fn (Forms\Get $get): bool => $get('type') == 'select')
                                    ->live(),
                                Forms\Components\TextInput::make('sort')
                                    ->label(__('fields::app.form.fields.sort-order'))
                                    ->required()
                                    ->integer()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Section::make(__('fields::app.form.fields.resource'))
                            ->schema([
                                Forms\Components\Select::make('customizable_type')
                                    ->label(__('fields::app.form.fields.resource'))
                                    ->required()
                                    ->searchable()
                                    ->native(false)
                                    ->disabledOn('edit')
                                    ->options(fn () => collect(Filament::getResources())->filter(fn ($resource) => in_array('Webkul\Field\Filament\Traits\HasCustomFields', class_uses($resource)))->mapWithKeys(fn ($resource) => [
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
                    ->label(__('fields::app.table.columns.code'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('fields::app.table.columns.name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('fields::app.table.columns.type')),
                Tables\Columns\TextColumn::make('customizable_type')
                    ->label(__('fields::app.table.columns.resource'))
                    ->description(fn (Field $record): string => str($record->customizable_type)->afterLast('\\')->toString().__('fields::app.form.fields.resource')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('fields::app.table.columns.created-at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('fields::app.table.filters.type.label'))
                    ->options([
                        'text'          => __('fields::app.form.fields.types.text'),
                        'textarea'      => __('fields::app.form.fields.types.textarea'),
                        'select'        => __('fields::app.form.fields.types.select'),
                        'checkbox'      => __('fields::app.form.fields.types.checkbox'),
                        'radio'         => __('fields::app.form.fields.types.radio'),
                        'toggle'        => __('fields::app.form.fields.types.toggle'),
                        'checkbox_list' => __('fields::app.form.fields.types.checkbox-list'),
                        'datetime'      => __('fields::app.form.fields.types.datetime'),
                        'editor'        => __('fields::app.form.fields.types.editor'),
                        'markdown'      => __('fields::app.form.fields.types.markdown'),
                        'color'         => __('fields::app.form.fields.types.color'),
                    ]),
                Tables\Filters\SelectFilter::make('customizable_type')
                    ->label(__('fields::app.table.filters.resource.label'))
                    ->options(fn () => collect(Filament::getResources())->filter(fn ($resource) => in_array('Webkul\Field\Filament\Traits\HasCustomFields', class_uses($resource)))->mapWithKeys(fn ($resource) => [
                        $resource::getModel() => str($resource)->afterLast('\\')->toString(),
                    ])),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
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
            Forms\Components\Fieldset::make(__('fields::app.form.sections.validations'))
                ->schema([
                    Forms\Components\Repeater::make('validations')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Select::make('validation')
                                ->label(__('fields::app.form.fields.validation'))
                                ->searchable()
                                ->required()
                                ->distinct()
                                ->live()
                                ->options(fn (Forms\Get $get): array => static::getTypeFormValidations($get('../../../type'))),
                            Forms\Components\TextInput::make('field')
                                ->label(__('fields::app.form.fields.field'))
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('validation'), [
                                    'prohibitedIf',
                                    'prohibitedUnless',
                                    'requiredIf',
                                    'requiredUnless',
                                ])),
                            Forms\Components\TextInput::make('value')
                                ->label(__('fields::app.form.fields.value'))
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('validation'), [
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
                        ->addActionLabel(__('fields::app.form.actions.add-option'))
                        ->columns(3)
                        ->collapsible()
                        ->itemLabel(function (array $state, Forms\Get $get): ?string {
                            $validations = static::getTypeFormValidations($get('../type'));

                            return $validations[$state['validation']] ?? null;
                        }),
                ])
                ->columns(1),

            Forms\Components\Fieldset::make(__('fields::app.form.sections.additional-settings'))
                ->schema([
                    Forms\Components\Repeater::make('settings')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Select::make('setting')
                                ->label(__('fields::app.form.fields.setting'))
                                ->required()
                                ->distinct()
                                ->searchable()
                                ->live()
                                ->options(fn (Forms\Get $get): array => static::getTypeFormSettings($get('../../../type'))),
                            Forms\Components\TextInput::make('value')
                                ->label(__('fields::app.form.fields.value'))
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
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
                                ->label(__('fields::app.form.fields.value'))
                                ->required()
                                ->numeric()
                                ->minValue(0)
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
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
                                ->label(__('fields::app.form.fields.color'))
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'hintColor',
                                    'prefixIconColor',
                                    'suffixIconColor',
                                    'onColor',
                                    'offColor',
                                ]))
                                ->options([
                                    'danger'    => __('fields::app.form.fields.types.danger'),
                                    'info'      => __('fields::app.form.fields.types.info'),
                                    'primary'   => __('fields::app.form.fields.types.primary'),
                                    'secondary' => __('fields::app.form.fields.types.secondary'),
                                    'warning'   => __('fields::app.form.fields.types.warning'),
                                    'success'   => __('fields::app.form.fields.types.success'),
                                ]),
                            Forms\Components\Select::make('value')
                                ->label(__('fields::app.form.fields.value'))
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'gridDirection',
                                ]))
                                ->options([
                                    'row'    => __('fields::app.form.fields.row'),
                                    'column' => __('fields::app.form.fields.column'),
                                ]),
                            Forms\Components\Select::make('value')
                                ->label(__('fields::app.form.fields.value'))
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'inputMode',
                                ]))
                                ->options([
                                    'none'    => __('fields::app.form.fields.input-types.none'),
                                    'text'    => __('fields::app.form.fields.input-types.text'),
                                    'numeric' => __('fields::app.form.fields.input-types.numeric'),
                                    'decimal' => __('fields::app.form.fields.input-types.decimal'),
                                    'tel'     => __('fields::app.form.fields.input-types.tel'),
                                    'search'  => __('fields::app.form.fields.input-types.search'),
                                    'email'   => __('fields::app.form.fields.input-types.email'),
                                    'url'     => __('fields::app.form.fields.input-types.url'),
                                ]),
                        ])
                        ->addActionLabel(__('fields::app.form.actions.add-setting'))
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
            'gt'                 => __('fields::app.form.validations.common.gt'),
            'gte'                => __('fields::app.form.validations.common.gte'),
            'lt'                 => __('fields::app.form.validations.common.lt'),
            'lte'                => __('fields::app.form.validations.common.lte'),
            'maxSize'            => __('fields::app.form.validations.common.max-size'),
            'minSize'            => __('fields::app.form.validations.common.min-size'),
            'multipleOf'         => __('fields::app.form.validations.common.multiple-of'),
            'nullable'           => __('fields::app.form.validations.common.nullable'),
            'prohibited'         => __('fields::app.form.validations.common.prohibited'),
            'prohibitedIf'       => __('fields::app.form.validations.common.prohibited-if'),
            'prohibitedUnless'   => __('fields::app.form.validations.common.prohibited-unless'),
            'prohibits'          => __('fields::app.form.validations.common.prohibits'),
            'required'           => __('fields::app.form.validations.common.required'),
            'requiredIf'         => __('fields::app.form.validations.common.required-if'),
            'requiredIfAccepted' => __('fields::app.form.validations.common.required-if-accepted'),
            'requiredUnless'     => __('fields::app.form.validations.common.required-unless'),
            'requiredWith'       => __('fields::app.form.validations.common.required-with'),
            'requiredWithAll'    => __('fields::app.form.validations.common.required-with-all'),
            'requiredWithout'    => __('fields::app.form.validations.common.required-without'),
            'requiredWithoutAll' => __('fields::app.form.validations.common.required-without-all'),
            'rules'              => __('fields::app.form.validations.common.rules'),
            'unique'             => __('fields::app.form.validations.common.unique'),
        ];

        $typeValidations = match ($type) {
            'text' => [
                'alphaDash'       => __('fields::app.form.validations.text.alpha-dash'),
                'alphaNum'        => __('fields::app.form.validations.text.alpha-num'),
                'ascii'           => __('fields::app.form.validations.text.ascii'),
                'doesntEndWith'   => __('fields::app.form.validations.text.doesnt-end-with'),
                'doesntStartWith' => __('fields::app.form.validations.text.doesnt-start-with'),
                'endsWith'        => __('fields::app.form.validations.text.ends-with'),
                'filled'          => __('fields::app.form.validations.text.filled'),
                'ip'              => __('fields::app.form.validations.text.ip'),
                'ipv4'            => __('fields::app.form.validations.text.ipv4'),
                'ipv6'            => __('fields::app.form.validations.text.ipv6'),
                'length'          => __('fields::app.form.validations.text.length'),
                'macAddress'      => __('fields::app.form.validations.text.mac-address'),
                'maxLength'       => __('fields::app.form.validations.text.max-length'),
                'minLength'       => __('fields::app.form.validations.text.min-length'),
                'regex'           => __('fields::app.form.validations.text.regex'),
                'startsWith'      => __('fields::app.form.validations.text.starts-with'),
                'ulid'            => __('fields::app.form.validations.text.ulid'),
                'uuid'            => __('fields::app.form.validations.text.uuid'),
            ],

            'textarea' => [
                'filled'    => __('fields::app.form.validations.textarea.filled'),
                'maxLength' => __('fields::app.form.validations.textarea.max-length'),
                'minLength' => __('fields::app.form.validations.textarea.min-length'),
            ],

            'select' => [
                'different' => __('fields::app.form.validations.select.different'),
                'exists'    => __('fields::app.form.validations.select.exists'),
                'in'        => __('fields::app.form.validations.select.in'),
                'notIn'     => __('fields::app.form.validations.select.not-in'),
                'same'      => __('fields::app.form.validations.select.same'),
            ],

            'radio' => [],

            'checkbox' => [
                'accepted' => __('fields::app.form.validations.checkbox.accepted'),
                'declined' => __('fields::app.form.validations.checkbox.declined'),
            ],

            'toggle' => [
                'accepted' => __('fields::app.form.validations.toggle.accepted'),
                'declined' => __('fields::app.form.validations.toggle.declined'),
            ],

            'checkbox_list' => [
                'in'       => __('fields::app.form.validations.checkbox-list.in'),
                'maxItems' => __('fields::app.form.validations.checkbox-list.max-items'),
                'minItems' => __('fields::app.form.validations.checkbox-list.min-items'),
            ],

            'datetime' => [
                'after'         => __('fields::app.form.validations.datetime.after'),
                'afterOrEqual'  => __('fields::app.form.validations.datetime.after-or-equal'),
                'before'        => __('fields::app.form.validations.datetime.before'),
                'beforeOrEqual' => __('fields::app.form.validations.datetime.before-or-equal'),
            ],

            'editor' => [
                'filled'    => __('fields::app.form.validations.editor.filled'),
                'maxLength' => __('fields::app.form.validations.editor.max-length'),
                'minLength' => __('fields::app.form.validations.editor.min-length'),
            ],

            'markdown' => [
                'filled'    => __('fields::app.form.validations.markdown.filled'),
                'maxLength' => __('fields::app.form.validations.markdown.max-length'),
                'minLength' => __('fields::app.form.validations.markdown.min-length'),
            ],

            'color' => [
                'hexColor' => __('fields::app.form.validations.color.hex-color'),
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
                'autocapitalize'  => __('fields::app.form.settings.text.autocapitalize'),
                'autocomplete'    => __('fields::app.form.settings.text.autocomplete'),
                'autofocus'       => __('fields::app.form.settings.text.autofocus'),
                'default'         => __('fields::app.form.settings.text.default'),
                'disabled'        => __('fields::app.form.settings.text.disabled'),
                'helperText'      => __('fields::app.form.settings.text.helper-text'),
                'hint'            => __('fields::app.form.settings.text.hint'),
                'hintColor'       => __('fields::app.form.settings.text.hint-color'),
                'hintIcon'        => __('fields::app.form.settings.text.hint-icon'),
                'id'              => __('fields::app.form.settings.text.id'),
                'inputMode'       => __('fields::app.form.settings.text.input-mode'),
                'mask'            => __('fields::app.form.settings.text.mask'),
                'placeholder'     => __('fields::app.form.settings.text.placeholder'),
                'prefix'          => __('fields::app.form.settings.text.prefix'),
                'prefixIcon'      => __('fields::app.form.settings.text.prefix-icon'),
                'prefixIconColor' => __('fields::app.form.settings.text.prefix-icon-color'),
                'readOnly'        => __('fields::app.form.settings.text.read-only'),
                'step'            => __('fields::app.form.settings.text.step'),
                'suffix'          => __('fields::app.form.settings.text.suffix'),
                'suffixIcon'      => __('fields::app.form.settings.text.suffix-icon'),
                'suffixIconColor' => __('fields::app.form.settings.text.suffix-icon-color'),
            ],

            'textarea' => [
                'autofocus'   => __('fields::app.form.settings.textarea.autofocus'),
                'autosize'    => __('fields::app.form.settings.textarea.autosize'),
                'cols'        => __('fields::app.form.settings.textarea.cols'),
                'default'     => __('fields::app.form.settings.textarea.default'),
                'disabled'    => __('fields::app.form.settings.textarea.disabled'),
                'helperText'  => __('fields::app.form.settings.textarea.helper-text'),
                'hint'        => __('fields::app.form.settings.textarea.hint'),
                'hintColor'   => __('fields::app.form.settings.textarea.hint-color'),
                'hintIcon'    => __('fields::app.form.settings.textarea.hinticon'),
                'id'          => __('fields::app.form.settings.textarea.id'),
                'placeholder' => __('fields::app.form.settings.textarea.placeholder'),
                'readOnly'    => __('fields::app.form.settings.textarea.read-only'),
                'rows'        => __('fields::app.form.settings.textarea.rows'),
            ],

            'select' => [
                'default'                => __('fields::app.form.settings.select.default'),
                'disabled'               => __('fields::app.form.settings.select.disabled'),
                'helperText'             => __('fields::app.form.settings.select.helper-text'),
                'hint'                   => __('fields::app.form.settings.select.hint'),
                'hintColor'              => __('fields::app.form.settings.select.hint-color'),
                'hintIcon'               => __('fields::app.form.settings.select.hint-icon'),
                'id'                     => __('fields::app.form.settings.select.id'),
                'loadingMessage'         => __('fields::app.form.settings.select.loading-message'),
                'noSearchResultsMessage' => __('fields::app.form.settings.select.no-search-results-message'),
                'optionsLimit'           => __('fields::app.form.settings.select.options-limit'),
                'preload'                => __('fields::app.form.settings.select.preload'),
                'searchable'             => __('fields::app.form.settings.select.searchable'),
                'searchDebounce'         => __('fields::app.form.settings.select.search-debounce'),
                'searchingMessage'       => __('fields::app.form.settings.select.searching-message'),
                'searchPrompt'           => __('fields::app.form.settings.select.search-prompt'),
            ],

            'radio' => [
                'default'    => __('fields::app.form.settings.radio.default'),
                'disabled'   => __('fields::app.form.settings.radio.disabled'),
                'helperText' => __('fields::app.form.settings.radio.helper-text'),
                'hint'       => __('fields::app.form.settings.radio.hint'),
                'hintColor'  => __('fields::app.form.settings.radio.hint-color'),
                'hintIcon'   => __('fields::app.form.settings.radio.hint-icon'),
                'id'         => __('fields::app.form.settings.radio.id'),
            ],

            'checkbox' => [
                'default'    => __('fields::app.form.settings.checkbox.default'),
                'disabled'   => __('fields::app.form.settings.checkbox.disabled'),
                'helperText' => __('fields::app.form.settings.checkbox.helper-text'),
                'hint'       => __('fields::app.form.settings.checkbox.hint'),
                'hintColor'  => __('fields::app.form.settings.checkbox.hint-color'),
                'hintIcon'   => __('fields::app.form.settings.checkbox.hint-icon'),
                'id'         => __('fields::app.form.settings.checkbox.id'),
                'inline'     => __('fields::app.form.settings.checkbox.inline'),
            ],

            'toggle' => [
                'default'    => __('fields::app.form.settings.toggle.default'),
                'disabled'   => __('fields::app.form.settings.toggle.disabled'),
                'helperText' => __('fields::app.form.settings.toggle.helper-text'),
                'hint'       => __('fields::app.form.settings.toggle.hint'),
                'hintColor'  => __('fields::app.form.settings.toggle.hint-color'),
                'hintIcon'   => __('fields::app.form.settings.toggle.hint-icon'),
                'id'         => __('fields::app.form.settings.toggle.id'),
                'offColor'   => __('fields::app.form.settings.toggle.off-color'),
                'offIcon'    => __('fields::app.form.settings.toggle.off-icon'),
                'onColor'    => __('fields::app.form.settings.toggle.on-color'),
                'onIcon'     => __('fields::app.form.settings.toggle.on-icon'),
            ],

            'checkbox-list' => [
                'bulkToggleable'         => __('fields::app.form.settings.checkbox-list.bulk-toggleable'),
                'columns'                => __('fields::app.form.settings.checkbox-list.columns'),
                'default'                => __('fields::app.form.settings.checkbox-list.default'),
                'disabled'               => __('fields::app.form.settings.checkbox-list.disabled'),
                'gridDirection'          => __('fields::app.form.settings.checkbox-list.grid-direction'),
                'helperText'             => __('fields::app.form.settings.checkbox-list.helper-text'),
                'hint'                   => __('fields::app.form.settings.checkbox-list.hint'),
                'hintColor'              => __('fields::app.form.settings.checkbox-list.hint-color'),
                'hintIcon'               => __('fields::app.form.settings.checkbox-list.hint-icon'),
                'id'                     => __('fields::app.form.settings.checkbox-list.id'),
                'maxItems'               => __('fields::app.form.settings.checkbox-list.max-items'),
                'minItems'               => __('fields::app.form.settings.checkbox-list.min-items'),
                'noSearchResultsMessage' => __('fields::app.form.settings.checkbox-list.no-search-results-message'),
                'searchable'             => __('fields::app.form.settings.checkbox-list.searchable'),
            ],

            'datetime' => [
                'closeOnDateSelection'   => __('fields::app.form.settings.datetime.close-on-date-selection'),
                'default'                => __('fields::app.form.settings.datetime.default'),
                'disabled'               => __('fields::app.form.settings.datetime.disabled'),
                'disabledDates'          => __('fields::app.form.settings.datetime.disabled-dates'),
                'displayFormat'          => __('fields::app.form.settings.datetime.display-format'),
                'firstDayOfWeek'         => __('fields::app.form.settings.datetime.first-day-of-week'),
                'format'                 => __('fields::app.form.settings.datetime.format'),
                'helperText'             => __('fields::app.form.settings.datetime.helper-text'),
                'hint'                   => __('fields::app.form.settings.datetime.hint'),
                'hintColor'              => __('fields::app.form.settings.datetime.hint-color'),
                'hintIcon'               => __('fields::app.form.settings.datetime.hint-icon'),
                'hoursStep'              => __('fields::app.form.settings.datetime.hours-step'),
                'id'                     => __('fields::app.form.settings.datetime.id'),
                'locale'                 => __('fields::app.form.settings.datetime.locale'),
                'minutesStep'            => __('fields::app.form.settings.datetime.minutes-step'),
                'seconds'                => __('fields::app.form.settings.datetime.seconds'),
                'secondsStep'            => __('fields::app.form.settings.datetime.seconds-step'),
                'timezone'               => __('fields::app.form.settings.datetime.timezone'),
                'weekStartsOnMonday'     => __('fields::app.form.settings.datetime.week-starts-on-monday'),
                'weekStartsOnSunday'     => __('fields::app.form.settings.datetime.week-starts-on-sunday'),
            ],

            'editor' => [
                'default'     => __('fields::app.form.settings.editor.default'),
                'disabled'    => __('fields::app.form.settings.editor.disabled'),
                'helperText'  => __('fields::app.form.settings.editor.helper-text'),
                'hint'        => __('fields::app.form.settings.editor.hint'),
                'hintColor'   => __('fields::app.form.settings.editor.hint-color'),
                'hintIcon'    => __('fields::app.form.settings.editor.hint-icon'),
                'id'          => __('fields::app.form.settings.editor.id'),
                'placeholder' => __('fields::app.form.settings.editor.placeholder'),
                'readOnly'    => __('fields::app.form.settings.editor.read-only'),
            ],

            'markdown' => [
                'default'     => __('fields::app.form.settings.markdown.default'),
                'disabled'    => __('fields::app.form.settings.markdown.disabled'),
                'helperText'  => __('fields::app.form.settings.markdown.helper-text'),
                'hint'        => __('fields::app.form.settings.markdown.hint'),
                'hintColor'   => __('fields::app.form.settings.markdown.hint-color'),
                'hintIcon'    => __('fields::app.form.settings.markdown.hint-icon'),
                'id'          => __('fields::app.form.settings.markdown.id'),
                'placeholder' => __('fields::app.form.settings.markdown.placeholder'),
                'readOnly'    => __('fields::app.form.settings.markdown.read-only'),
            ],

            'color' => [
                'default'    => __('fields::app.form.settings.color.default'),
                'disabled'   => __('fields::app.form.settings.color.disabled'),
                'helperText' => __('fields::app.form.settings.color.helper-text'),
                'hint'       => __('fields::app.form.settings.color.hint'),
                'hintColor'  => __('fields::app.form.settings.color.hint-color'),
                'hintIcon'   => __('fields::app.form.settings.color.hint-icon'),
                'hsl'        => __('fields::app.form.settings.color.hsl'),
                'id'         => __('fields::app.form.settings.color.id'),
                'rgb'        => __('fields::app.form.settings.color.rgb'),
                'rgba'       => __('fields::app.form.settings.color.rgba'),
            ],

            'file' => [
                'acceptedFileTypes'                => __('fields::app.form.settings.file.accepted-file-types'),
                'appendFiles'                      => __('fields::app.form.settings.file.append-files'),
                'deletable'                        => __('fields::app.form.settings.file.deletable'),
                'directory'                        => __('fields::app.form.settings.file.directory'),
                'downloadable'                     => __('fields::app.form.settings.file.downloadable'),
                'fetchFileInformation'             => __('fields::app.form.settings.file.fetch-file-information'),
                'fileAttachmentsDirectory'         => __('fields::app.form.settings.file.file-attachment-directory'),
                'fileAttachmentsVisibility'        => __('fields::app.form.settings.file.file-attachments-visibility'),
                'image'                            => __('fields::app.form.settings.file.image'),
                'imageCropAspectRatio'             => __('fields::app.form.settings.file.image-crop-aspect-ratio'),
                'imageEditor'                      => __('fields::app.form.settings.file.image-editor'),
                'imageEditorAspectRatios'          => __('fields::app.form.settings.file.image-editor-aspect-ratios'),
                'imageEditorEmptyFillColor'        => __('fields::app.form.settings.file.image-editor-empty-fill-color'),
                'imageEditorMode'                  => __('fields::app.form.settings.file.image-editor-mode'),
                'imagePreviewHeight'               => __('fields::app.form.settings.file.image-preview-height'),
                'imageResizeMode'                  => __('fields::app.form.settings.file.image-resize-mode'),
                'imageResizeTargetHeight'          => __('fields::app.form.settings.file.image-resize-target-height'),
                'imageResizeTargetWidth'           => __('fields::app.form.settings.file.image-resize-target-width'),
                'loadingIndicatorPosition'         => __('fields::app.form.settings.file.loading-indicator-position'),
                'moveFiles'                        => __('fields::app.form.settings.file.move-files'),
                'openable'                         => __('fields::app.form.settings.file.openable'),
                'orientImagesFromExif'             => __('fields::app.form.settings.file.orient-images-from-exif'),
                'panelAspectRatio'                 => __('fields::app.form.settings.file.panel-aspect-ratio'),
                'panelLayout'                      => __('fields::app.form.settings.file.panel-layout'),
                'previewable'                      => __('fields::app.form.settings.file.previewable'),
                'removeUploadedFileButtonPosition' => __('fields::app.form.settings.file.remove-uploaded-file-button-position'),
                'reorderable'                      => __('fields::app.form.settings.file.reorderable'),
                'storeFiles'                       => __('fields::app.form.settings.file.store-files'),
                'uploadButtonPosition'             => __('fields::app.form.settings.file.upload-button-position'),
                'uploadingMessage'                 => __('fields::app.form.settings.file.uploading-message'),
                'uploadProgressIndicatorPosition'  => __('fields::app.form.settings.file.upload-progress-indicator-position'),
                'visibility'                       => __('fields::app.form.settings.file.visibility'),
            ],
        };
    }

    public static function getTableSettingsSchema(): array
    {
        return [
            Forms\Components\Toggle::make('use_in_table')
                ->label(__('fields::app.form.fields.use-in-table'))
                ->required()
                ->live(),
            Forms\Components\Repeater::make('table_settings')
                ->hiddenLabel()
                ->visible(fn (Forms\Get $get): bool => $get('use_in_table'))
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->label(__('fields::app.form.fields.setting'))
                        ->searchable()
                        ->required()
                        ->distinct()
                        ->live()
                        ->options(fn (Forms\Get $get): array => static::getTypeTableSettings($get('../../type'))),
                    Forms\Components\TextInput::make('value')
                        ->label('Value')
                        ->label(__('fields::app.form.fields.value'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
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
                        ->label(__('fields::app.form.fields.value'))
                        ->label('Color')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'color',
                            'iconColor',
                        ]))
                        ->options([
                            'danger'    => __('fields::app.form.fields.types.danger'),
                            'info'      => __('fields::app.form.fields.types.info'),
                            'primary'   => __('fields::app.form.fields.types.primary'),
                            'secondary' => __('fields::app.form.fields.types.secondary'),
                            'warning'   => __('fields::app.form.fields.types.warning'),
                            'success'   => __('fields::app.form.fields.types.success'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('fields::app.form.fields.alignment'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'alignment',
                            'verticalAlignment',
                        ]))
                        ->options([
                            Alignment::Start->value   => __('fields::app.form.fields.types.start'),
                            Alignment::Left->value    => __('fields::app.form.fields.types.left'),
                            Alignment::Center->value  => __('fields::app.form.fields.types.center'),
                            Alignment::End->value     => __('fields::app.form.fields.types.end'),
                            Alignment::Right->value   => __('fields::app.form.fields.types.right'),
                            Alignment::Justify->value => __('fields::app.form.fields.types.justify'),
                            Alignment::Between->value => __('fields::app.form.fields.types.between'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('fields::app.form.fields.font-weight'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'weight',
                        ]))
                        ->options([
                            FontWeight::Thin->name       => __('fields::app.form.fields.types.thin'),
                            FontWeight::ExtraLight->name => __('fields::app.form.fields.types.extra-light'),
                            FontWeight::Light->name      => __('fields::app.form.fields.types.light'),
                            FontWeight::Normal->name     => __('fields::app.form.fields.types.normal'),
                            FontWeight::Medium->name     => __('fields::app.form.fields.types.medium'),
                            FontWeight::SemiBold->name   => __('fields::app.form.fields.types.semi-bold'),
                            FontWeight::Bold->name       => __('fields::app.form.fields.types.bold'),
                            FontWeight::ExtraBold->name  => __('fields::app.form.fields.types.extra-bold'),
                            FontWeight::Black->name      => __('fields::app.form.fields.types.black'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('fields::app.form.fields.icon-position'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'iconPosition',
                        ]))
                        ->options([
                            IconPosition::Before->value => __('fields::app.form.fields.types.before'),
                            IconPosition::After->value  => __('fields::app.form.fields.types.after'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label(__('fields::app.form.fields.size'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'size',
                        ]))
                        ->options([
                            TextColumn\TextColumnSize::Small->name  => __('fields::app.form.fields.types.small'),
                            TextColumn\TextColumnSize::Medium->name => __('fields::app.form.fields.types.medium'),
                            TextColumn\TextColumnSize::Large->name  => __('fields::app.form.fields.types.large'),
                        ]),

                    Forms\Components\TextInput::make('value')
                        ->label(__('fields::app.form.fields.value'))
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'limit',
                            'words',
                            'lineClamp',
                            'copyMessageDuration',
                        ])),
                ])
                ->addActionLabel(__('fields::app.form.actions.add-setting'))
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
            'alignEnd'             => __('fields::app.form.settings.common.align-end'),
            'alignment'            => __('fields::app.form.settings.common.alignment'),
            'alignStart'           => __('fields::app.form.settings.common.align-start'),
            'badge'                => __('fields::app.form.settings.common.badge'),
            'boolean'              => __('fields::app.form.settings.common.boolean'),
            'color'                => __('fields::app.form.settings.common.color'),
            'copyable'             => __('fields::app.form.settings.common.copyable'),
            'copyMessage'          => __('fields::app.form.settings.common.copy-message'),
            'copyMessageDuration'  => __('fields::app.form.settings.common.copy-message-duration'),
            'default'              => __('fields::app.form.settings.common.default'),
            'filterable'           => __('fields::app.form.settings.common.filterable'),
            'groupable'            => __('fields::app.form.settings.common.groupable'),
            'grow'                 => __('fields::app.form.settings.common.grow'),
            'icon'                 => __('fields::app.form.settings.common.icon'),
            'iconColor'            => __('fields::app.form.settings.common.icon-color'),
            'iconPosition'         => __('fields::app.form.settings.common.icon-position'),
            'label'                => __('fields::app.form.settings.common.label'),
            'limit'                => __('fields::app.form.settings.common.limit'),
            'lineClamp'            => __('fields::app.form.settings.common.line-clamp'),
            'money'                => __('fields::app.form.settings.common.money'),
            'placeholder'          => __('fields::app.form.settings.common.placeholder'),
            'prefix'               => __('fields::app.form.settings.common.prefix'),
            'searchable'           => __('fields::app.form.settings.common.searchable'),
            'size'                 => __('fields::app.form.settings.common.size'),
            'sortable'             => __('fields::app.form.settings.common.sortable'),
            'suffix'               => __('fields::app.form.settings.common.suffix'),
            'toggleable'           => __('fields::app.form.settings.common.toggleable'),
            'tooltip'              => __('fields::app.form.settings.common.tooltip'),
            'verticalAlignment'    => __('fields::app.form.settings.common.vertical-alignment'),
            'verticallyAlignStart' => __('fields::app.form.settings.common.vertically-align-start'),
            'weight'               => __('fields::app.form.settings.common.weight'),
            'width'                => __('fields::app.form.settings.common.width'),
            'words'                => __('fields::app.form.settings.common.words'),
            'wrapHeader'           => __('fields::app.form.settings.common.wrap-header'),
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'date'            => __('fields::app.form.settings.type.datetime.date'),
                'dateTime'        => __('fields::app.form.settings.type.datetime.date-time'),
                'dateTimeTooltip' => __('fields::app.form.settings.type.datetime.date-time-tooltip'),
                'since'           => __('fields::app.form.settings.type.datetime.since'),
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }

    public static function getInfolistSettingsSchema(): array
    {
        return [
            Forms\Components\Repeater::make('infolist_settings')
                ->label(__('fields::app.form.sections.infolist-settings'))
                ->hiddenLabel()
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->label(__('fields::app.form.fields.setting'))
                        ->searchable()
                        ->required()
                        ->distinct()
                        ->live()
                        ->options(fn (Forms\Get $get): array => static::getTypeInfolistSettings($get('../../type'))),
                    Forms\Components\TextInput::make('value')
                        ->label(__('fields::app.form.fields.value'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
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
                        ->label(__('fields::app.form.fields.color'))
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'color',
                            'iconColor',
                            'hintColor',
                            'trueColor',
                            'falseColor',
                        ]))
                        ->options([
                            'danger'    => __('fields::app.form.fields.types.danger'),
                            'info'      => __('fields::app.form.fields.types.info'),
                            'primary'   => __('fields::app.form.fields.types.primary'),
                            'secondary' => __('fields::app.form.fields.types.secondary'),
                            'warning'   => __('fields::app.form.fields.types.warning'),
                            'success'   => __('fields::app.form.fields.types.success'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Font Weight')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'weight',
                        ]))
                        ->options([
                            FontWeight::Thin->name       => __('fields::app.form.fields.types.thin'),
                            FontWeight::ExtraLight->name => __('fields::app.form.fields.types.extra-light'),
                            FontWeight::Light->name      => __('fields::app.form.fields.types.light'),
                            FontWeight::Normal->name     => __('fields::app.form.fields.types.normal'),
                            FontWeight::Medium->name     => __('fields::app.form.fields.types.medium'),
                            FontWeight::SemiBold->name   => __('fields::app.form.fields.types.semi-bold'),
                            FontWeight::Bold->name       => __('fields::app.form.fields.types.bold'),
                            FontWeight::ExtraBold->name  => __('fields::app.form.fields.types.extra-bold'),
                            FontWeight::Black->name      => __('fields::app.form.fields.types.black'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Icon Position')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'iconPosition',
                        ]))
                        ->options([
                            IconPosition::Before->value => __('fields::app.form.fields.types.before'),
                            IconPosition::After->value  => __('fields::app.form.fields.types.after'),
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Size')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'size',
                        ]))
                        ->options([
                            TextColumn\TextColumnSize::Small->name  => __('fields::app.form.fields.types.small'),
                            TextColumn\TextColumnSize::Medium->name => __('fields::app.form.fields.types.medium'),
                            TextColumn\TextColumnSize::Large->name  => __('fields::app.form.fields.types.large'),
                        ]),

                    Forms\Components\TextInput::make('value')
                        ->label(__('fields::app.form.fields.value'))
                        ->required()
                        ->numeric()
                        ->minValue(0)
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'limit',
                            'words',
                            'lineClamp',
                            'copyMessageDuration',
                            'columnSpan',
                            'limitList',
                        ])),
                ])
                ->addActionLabel(__('fields::app.form.actions.add-setting'))
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
            'badge'               => __('fields::app.form.settings.common.badge'),
            'color'               => __('fields::app.form.settings.common.color'),
            'copyable'            => __('fields::app.form.settings.common.copyable'),
            'copyMessage'         => __('fields::app.form.settings.common.copy-message'),
            'copyMessageDuration' => __('fields::app.form.settings.common.copy-message-duration'),
            'default'             => __('fields::app.form.settings.common.default'),
            'icon'                => __('fields::app.form.settings.common.icon'),
            'iconColor'           => __('fields::app.form.settings.common.icon-color'),
            'iconPosition'        => __('fields::app.form.settings.common.icon-position'),
            'label'               => __('fields::app.form.settings.common.label'),
            'limit'               => __('fields::app.form.settings.common.limit'),
            'lineClamp'           => __('fields::app.form.settings.common.limit-clamp'),
            'money'               => __('fields::app.form.settings.common.money'),
            'placeholder'         => __('fields::app.form.settings.common.placeholder'),
            'size'                => __('fields::app.form.settings.common.size'),
            'tooltip'             => __('fields::app.form.settings.common.tooltip'),
            'weight'              => __('fields::app.form.settings.common.weight'),
            'words'               => __('fields::app.form.settings.common.words'),
            'columnSpan'          => __('fields::app.form.settings.common.column-span'),
            'helperText'          => __('fields::app.form.settings.common.helper-text'),
            'hint'                => __('fields::app.form.settings.common.hint'),
            'hintColor'           => __('fields::app.form.settings.common.hint-color'),
            'hintIcon'            => __('fields::app.form.settings.common.hint-icon'),
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'date'            => __('fields::app.form.settings.type.datetime.date'),
                'dateTime'        => __('fields::app.form.settings.type.datetime.date-time'),
                'dateTimeTooltip' => __('fields::app.form.settings.type.datetime.date-time-tooltip'),
                'since'           => __('fields::app.form.settings.type.datetime.since'),
            ],

            'checkbox_list' => [
                'separator'             => __('fields::app.form.settings.type.checkbox-list.separator'),
                'listWithLineBreaks'    => __('fields::app.form.settings.type.checkbox-list.list-with-line-breaks'),
                'bulleted'              => __('fields::app.form.settings.type.checkbox-list.bulleted'),
                'limitList'             => __('fields::app.form.settings.type.checkbox-list.limit-list'),
                'expandableLimitedList' => __('fields::app.form.settings.type.checkbox-list.expandable-limited-list'),
            ],

            'select' => [
                'separator'             => __('fields::app.form.settings.type.select.separator'),
                'listWithLineBreaks'    => __('fields::app.form.settings.type.select.list-with-line-breaks'),
                'bulleted'              => __('fields::app.form.settings.type.select.bulleted'),
                'limitList'             => __('fields::app.form.settings.type.select.limit-list'),
                'expandableLimitedList' => __('fields::app.form.settings.type.select.expandable-limited-list'),
            ],

            'checkbox' => [
                'boolean'    => __('fields::app.form.settings.type.checkbox.boolean'),
                'falseIcon'  => __('fields::app.form.settings.type.checkbox.false-icon'),
                'trueIcon'   => __('fields::app.form.settings.type.checkbox.true-icon'),
                'trueColor'  => __('fields::app.form.settings.type.checkbox.true-color'),
                'falseColor' => __('fields::app.form.settings.type.checkbox.false-color'),
            ],

            'toggle' => [
                'boolean'    => __('fields::app.form.settings.type.toggle.boolean'),
                'falseIcon'  => __('fields::app.form.settings.type.toggle.false-icon'),
                'trueIcon'   => __('fields::app.form.settings.type.toggle.true-icon'),
                'trueColor'  => __('fields::app.form.settings.type.toggle.true-color'),
                'falseColor' => __('fields::app.form.settings.type.toggle.false-color'),
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }
}
