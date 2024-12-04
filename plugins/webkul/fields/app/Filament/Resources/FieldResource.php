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
                    ->label(__('field::app.table.columns.created_at')),
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
                        'checkbox_list' => __('field::app.form.fields.types.checkbox_list'),
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
            'maxSize'            => __('field::app.form.validations.common.maxSize'),
            'minSize'            => __('field::app.form.validations.common.minSize'),
            'multipleOf'         => __('field::app.form.validations.common.multipleOf'),
            'nullable'           => __('field::app.form.validations.common.nullable'),
            'prohibited'         => __('field::app.form.validations.common.prohibited'),
            'prohibitedIf'       => __('field::app.form.validations.common.prohibitedIf'),
            'prohibitedUnless'   => __('field::app.form.validations.common.prohibitedUnless'),
            'prohibits'          => __('field::app.form.validations.common.prohibits'),
            'required'           => __('field::app.form.validations.common.required'),
            'requiredIf'         => __('field::app.form.validations.common.requiredIf'),
            'requiredIfAccepted' => __('field::app.form.validations.common.requiredIfAccepted'),
            'requiredUnless'     => __('field::app.form.validations.common.requiredUnless'),
            'requiredWith'       => __('field::app.form.validations.common.requiredWith'),
            'requiredWithAll'    => __('field::app.form.validations.common.requiredWithAll'),
            'requiredWithout'    => __('field::app.form.validations.common.requiredWithout'),
            'requiredWithoutAll' => __('field::app.form.validations.common.requiredWithoutAll'),
            'rules'              => __('field::app.form.validations.common.rules'),
            'unique'             => __('field::app.form.validations.common.unique'),
        ];

        $typeValidations = match ($type) {
            'text' => [
                'alphaDash'       => __('field::app.form.validations.text.alphaDash'),
                'alphaNum'        => __('field::app.form.validations.text.alphaNum'),
                'ascii'           => __('field::app.form.validations.text.ascii'),
                'doesntEndWith'   => __('field::app.form.validations.text.doesntEndWith'),
                'doesntStartWith' => __('field::app.form.validations.text.doesntStartWith'),
                'endsWith'        => __('field::app.form.validations.text.endsWith'),
                'filled'          => __('field::app.form.validations.text.filled'),
                'ip'              => __('field::app.form.validations.text.ip'),
                'ipv4'            => __('field::app.form.validations.text.ipv4'),
                'ipv6'            => __('field::app.form.validations.text.ipv6'),
                'length'          => __('field::app.form.validations.text.length'),
                'macAddress'      => __('field::app.form.validations.text.macAddress'),
                'maxLength'       => __('field::app.form.validations.text.maxLength'),
                'minLength'       => __('field::app.form.validations.text.minLength'),
                'regex'           => __('field::app.form.validations.text.regex'),
                'startsWith'      => __('field::app.form.validations.text.startsWith'),
                'ulid'            => __('field::app.form.validations.text.ulid'),
                'uuid'            => __('field::app.form.validations.text.uuid'),
            ],

            'textarea' => [
                'filled'    => __('field::app.form.validations.textarea.filled'),
                'maxLength' => __('field::app.form.validations.textarea.maxLength'),
                'minLength' => __('field::app.form.validations.textarea.minLength'),
            ],

            'select' => [
                'different' => __('field::app.form.validations.select.different'),
                'exists'    => __('field::app.form.validations.select.exists'),
                'in'        => __('field::app.form.validations.select.in'),
                'notIn'     => __('field::app.form.validations.select.notIn'),
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
                'in'       => __('field::app.form.validations.checkbox_list.in'),
                'maxItems' => __('field::app.form.validations.checkbox_list.maxItems'),
                'minItems' => __('field::app.form.validations.checkbox_list.minItems'),
            ],

            'datetime' => [
                'after'         => __('field::app.form.validations.datetime.after'),
                'afterOrEqual'  => __('field::app.form.validations.datetime.afterOrEqual'),
                'before'        => __('field::app.form.validations.datetime.before'),
                'beforeOrEqual' => __('field::app.form.validations.datetime.beforeOrEqual'),
            ],

            'editor' => [
                'filled'    => __('field::app.form.validations.editor.filled'),
                'maxLength' => __('field::app.form.validations.editor.maxLength'),
                'minLength' => __('field::app.form.validations.editor.minLength'),
            ],

            'markdown' => [
                'filled'    => __('field::app.form.validations.markdown.filled'),
                'maxLength' => __('field::app.form.validations.markdown.maxLength'),
                'minLength' => __('field::app.form.validations.markdown.minLength'),
            ],

            'color' => [
                'hexColor' => __('field::app.form.validations.color.hexColor'),
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
                'helperText'      => __('field::app.form.settings.text.helperText'),
                'hint'            => __('field::app.form.settings.text.hint'),
                'hintColor'       => __('field::app.form.settings.text.hintColor'),
                'hintIcon'        => __('field::app.form.settings.text.hintIcon'),
                'id'              => __('field::app.form.settings.text.id'),
                'inputMode'       => __('field::app.form.settings.text.inputMode'),
                'mask'            => __('field::app.form.settings.text.mask'),
                'placeholder'     => __('field::app.form.settings.text.placeholder'),
                'prefix'          => __('field::app.form.settings.text.prefix'),
                'prefixIcon'      => __('field::app.form.settings.text.prefixIcon'),
                'prefixIconColor' => __('field::app.form.settings.text.prefixIconColor'),
                'readOnly'        => __('field::app.form.settings.text.readOnly'),
                'step'            => __('field::app.form.settings.text.step'),
                'suffix'          => __('field::app.form.settings.text.suffix'),
                'suffixIcon'      => __('field::app.form.settings.text.suffixIcon'),
                'suffixIconColor' => __('field::app.form.settings.text.suffixIconColor'),
            ],

            'textarea' => [
                'autofocus'   => __('field::app.form.settings.textarea.autofocus'),
                'autosize'    => __('field::app.form.settings.textarea.autosize'),
                'cols'        => __('field::app.form.settings.textarea.cols'),
                'default'     => __('field::app.form.settings.textarea.default'),
                'disabled'    => __('field::app.form.settings.textarea.disabled'),
                'helperText'  => __('field::app.form.settings.textarea.helperText'),
                'hint'        => __('field::app.form.settings.textarea.hint'),
                'hintColor'   => __('field::app.form.settings.textarea.hintColor'),
                'hintIcon'    => __('field::app.form.settings.textarea.hintIcon'),
                'id'          => __('field::app.form.settings.textarea.id'),
                'placeholder' => __('field::app.form.settings.textarea.placeholder'),
                'readOnly'    => __('field::app.form.settings.textarea.readOnly'),
                'rows'        => __('field::app.form.settings.textarea.rows'),
            ],

            'select' => [
                'default'                => __('field::app.form.settings.select.default'),
                'disabled'               => __('field::app.form.settings.select.disabled'),
                'helperText'             => __('field::app.form.settings.select.helperText'),
                'hint'                   => __('field::app.form.settings.select.hint'),
                'hintColor'              => __('field::app.form.settings.select.hintColor'),
                'hintIcon'               => __('field::app.form.settings.select.hintIcon'),
                'id'                     => __('field::app.form.settings.select.id'),
                'loadingMessage'         => __('field::app.form.settings.select.loadingMessage'),
                'noSearchResultsMessage' => __('field::app.form.settings.select.noSearchResultsMessage'),
                'optionsLimit'           => __('field::app.form.settings.select.optionsLimit'),
                'preload'                => __('field::app.form.settings.select.preload'),
                'searchable'             => __('field::app.form.settings.select.searchable'),
                'searchDebounce'         => __('field::app.form.settings.select.searchDebounce'),
                'searchingMessage'       => __('field::app.form.settings.select.searchingMessage'),
                'searchPrompt'           => __('field::app.form.settings.select.searchPrompt'),
            ],

            'radio' => [
                'default'    => __('field::app.form.settings.radio.default'),
                'disabled'   => __('field::app.form.settings.radio.disabled'),
                'helperText' => __('field::app.form.settings.radio.helperText'),
                'hint'       => __('field::app.form.settings.radio.hint'),
                'hintColor'  => __('field::app.form.settings.radio.hintColor'),
                'hintIcon'   => __('field::app.form.settings.radio.hintIcon'),
                'id'         => __('field::app.form.settings.radio.id'),
            ],

            'checkbox' => [
                'default'    => __('field::app.form.settings.checkbox.default'),
                'disabled'   => __('field::app.form.settings.checkbox.disabled'),
                'helperText' => __('field::app.form.settings.checkbox.helperText'),
                'hint'       => __('field::app.form.settings.checkbox.hint'),
                'hintColor'  => __('field::app.form.settings.checkbox.hintColor'),
                'hintIcon'   => __('field::app.form.settings.checkbox.hintIcon'),
                'id'         => __('field::app.form.settings.checkbox.id'),
                'inline'     => __('field::app.form.settings.checkbox.inline'),
            ],

            'toggle' => [
                'default'    => __('field::app.form.settings.toggle.default'),
                'disabled'   => __('field::app.form.settings.toggle.disabled'),
                'helperText' => __('field::app.form.settings.toggle.helperText'),
                'hint'       => __('field::app.form.settings.toggle.hint'),
                'hintColor'  => __('field::app.form.settings.toggle.hintColor'),
                'hintIcon'   => __('field::app.form.settings.toggle.hintIcon'),
                'id'         => __('field::app.form.settings.toggle.id'),
                'offColor'   => __('field::app.form.settings.toggle.offColor'),
                'offIcon'    => __('field::app.form.settings.toggle.offIcon'),
                'onColor'    => __('field::app.form.settings.toggle.onColor'),
                'onIcon'     => __('field::app.form.settings.toggle.onIcon'),
            ],

            'checkbox-list' => [
                'bulkToggleable'         => __('field::app.form.settings.checkbox_list.bulkToggleable'),
                'columns'                => __('field::app.form.settings.checkbox_list.columns'),
                'default'                => __('field::app.form.settings.checkbox_list.default'),
                'disabled'               => __('field::app.form.settings.checkbox_list.disabled'),
                'gridDirection'          => __('field::app.form.settings.checkbox_list.gridDirection'),
                'helperText'             => __('field::app.form.settings.checkbox_list.helperText'),
                'hint'                   => __('field::app.form.settings.checkbox_list.hint'),
                'hintColor'              => __('field::app.form.settings.checkbox_list.hintColor'),
                'hintIcon'               => __('field::app.form.settings.checkbox_list.hintIcon'),
                'id'                     => __('field::app.form.settings.checkbox_list.id'),
                'maxItems'               => __('field::app.form.settings.checkbox_list.maxItems'),
                'minItems'               => __('field::app.form.settings.checkbox_list.minItems'),
                'noSearchResultsMessage' => __('field::app.form.settings.checkbox_list.noSearchResultsMessage'),
                'searchable'             => __('field::app.form.settings.checkbox_list.searchable'),
            ],

            'datetime' => [
                'closeOnDateSelection'   => __('field::app.form.settings.datetime.closeOnDateSelection'),
                'default'                => __('field::app.form.settings.datetime.default'),
                'disabled'               => __('field::app.form.settings.datetime.disabled'),
                'disabledDates'          => __('field::app.form.settings.datetime.disabledDates'),
                'displayFormat'          => __('field::app.form.settings.datetime.displayFormat'),
                'firstDayOfWeek'         => __('field::app.form.settings.datetime.firstDayOfWeek'),
                'format'                 => __('field::app.form.settings.datetime.format'),
                'helperText'             => __('field::app.form.settings.datetime.helperText'),
                'hint'                   => __('field::app.form.settings.datetime.hint'),
                'hintColor'              => __('field::app.form.settings.datetime.hintColor'),
                'hintIcon'               => __('field::app.form.settings.datetime.hintIcon'),
                'hoursStep'              => __('field::app.form.settings.datetime.hoursStep'),
                'id'                     => __('field::app.form.settings.datetime.id'),
                'locale'                 => __('field::app.form.settings.datetime.locale'),
                'minutesStep'            => __('field::app.form.settings.datetime.minutesStep'),
                'seconds'                => __('field::app.form.settings.datetime.seconds'),
                'secondsStep'            => __('field::app.form.settings.datetime.secondsStep'),
                'timezone'               => __('field::app.form.settings.datetime.timezone'),
                'weekStartsOnMonday'     => __('field::app.form.settings.datetime.weekStartsOnMonday'),
                'weekStartsOnSunday'     => __('field::app.form.settings.datetime.weekStartsOnSunday'),
            ],

            'editor' => [
                'default'     => __('field::app.form.settings.editor.default'),
                'disabled'    => __('field::app.form.settings.editor.disabled'),
                'helperText'  => __('field::app.form.settings.editor.helperText'),
                'hint'        => __('field::app.form.settings.editor.hint'),
                'hintColor'   => __('field::app.form.settings.editor.hintColor'),
                'hintIcon'    => __('field::app.form.settings.editor.hintIcon'),
                'id'          => __('field::app.form.settings.editor.id'),
                'placeholder' => __('field::app.form.settings.editor.placeholder'),
                'readOnly'    => __('field::app.form.settings.editor.readOnly'),
            ],

            'markdown' => [
                'default'     => __('field::app.form.settings.markdown.default'),
                'disabled'    => __('field::app.form.settings.markdown.disabled'),
                'helperText'  => __('field::app.form.settings.markdown.helperText'),
                'hint'        => __('field::app.form.settings.markdown.hint'),
                'hintColor'   => __('field::app.form.settings.markdown.hintColor'),
                'hintIcon'    => __('field::app.form.settings.markdown.hintIcon'),
                'id'          => __('field::app.form.settings.markdown.id'),
                'placeholder' => __('field::app.form.settings.markdown.placeholder'),
                'readOnly'    => __('field::app.form.settings.markdown.readOnly'),
            ],

            'color' => [
                'default'    => __('field::app.form.settings.color.default'),
                'disabled'   => __('field::app.form.settings.color.disabled'),
                'helperText' => __('field::app.form.settings.color.helperText'),
                'hint'       => __('field::app.form.settings.color.hint'),
                'hintColor'  => __('field::app.form.settings.color.hintColor'),
                'hintIcon'   => __('field::app.form.settings.color.hintIcon'),
                'hsl'        => __('field::app.form.settings.color.hsl'),
                'id'         => __('field::app.form.settings.color.id'),
                'rgb'        => __('field::app.form.settings.color.rgb'),
                'rgba'       => __('field::app.form.settings.color.rgba'),
            ],

            'file' => [
                'acceptedFileTypes'                => __('field::app.form.settings.file.acceptedFileTypes'),
                'appendFiles'                      => __('field::app.form.settings.file.appendFiles'),
                'deletable'                        => __('field::app.form.settings.file.deletable'),
                'directory'                        => __('field::app.form.settings.file.directory'),
                'downloadable'                     => __('field::app.form.settings.file.downloadable'),
                'fetchFileInformation'             => __('field::app.form.settings.file.fetchFileInformation'),
                'fileAttachmentsDirectory'         => __('field::app.form.settings.file.fileAttachmentsDirectory'),
                'fileAttachmentsVisibility'        => __('field::app.form.settings.file.fileAttachmentsVisibility'),
                'image'                            => __('field::app.form.settings.file.image'),
                'imageCropAspectRatio'             => __('field::app.form.settings.file.imageCropAspectRatio'),
                'imageEditor'                      => __('field::app.form.settings.file.imageEditor'),
                'imageEditorAspectRatios'          => __('field::app.form.settings.file.imageEditorAspectRatios'),
                'imageEditorEmptyFillColor'        => __('field::app.form.settings.file.imageEditorEmptyFillColor'),
                'imageEditorMode'                  => __('field::app.form.settings.file.imageEditorMode'),
                'imagePreviewHeight'               => __('field::app.form.settings.file.imagePreviewHeight'),
                'imageResizeMode'                  => __('field::app.form.settings.file.imageResizeMode'),
                'imageResizeTargetHeight'          => __('field::app.form.settings.file.imageResizeTargetHeight'),
                'imageResizeTargetWidth'           => __('field::app.form.settings.file.imageResizeTargetWidth'),
                'loadingIndicatorPosition'         => __('field::app.form.settings.file.loadingIndicatorPosition'),
                'moveFiles'                        => __('field::app.form.settings.file.moveFiles'),
                'openable'                         => __('field::app.form.settings.file.openable'),
                'orientImagesFromExif'             => __('field::app.form.settings.file.orientImagesFromExif'),
                'panelAspectRatio'                 => __('field::app.form.settings.file.panelAspectRatio'),
                'panelLayout'                      => __('field::app.form.settings.file.panelLayout'),
                'previewable'                      => __('field::app.form.settings.file.previewable'),
                'removeUploadedFileButtonPosition' => __('field::app.form.settings.file.removeUploadedFileButtonPosition'),
                'reorderable'                      => __('field::app.form.settings.file.reorderable'),
                'storeFiles'                       => __('field::app.form.settings.file.storeFiles'),
                'uploadButtonPosition'             => __('field::app.form.settings.file.uploadButtonPosition'),
                'uploadingMessage'                 => __('field::app.form.settings.file.uploadingMessage'),
                'uploadProgressIndicatorPosition'  => __('field::app.form.settings.file.uploadProgressIndicatorPosition'),
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
                        ->label(__('field::app.form.fields.settings'))
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
            'alignEnd'             => 'Align End',
            'alignment'            => 'Alignment',
            'alignStart'           => 'Align Start',
            'badge'                => 'Badge',
            'boolean'              => 'Boolean',
            'color'                => 'Color', // TODO:
            'copyable'             => 'Copyable',
            'copyMessage'          => 'Copy Message',
            'copyMessageDuration'  => 'Copy Message Duration',
            'default'              => 'Default',
            'filterable'           => 'Filterable',
            'groupable'            => 'Groupable',
            'grow'                 => 'Grow',
            'icon'                 => 'Icon',
            'iconColor'            => 'Icon Color',
            'iconPosition'         => 'Icon Position',
            'label'                => 'Label',
            'limit'                => 'Limit',
            'lineClamp'            => 'Line Clamp',
            'money'                => 'Money',
            'placeholder'          => 'Placeholder',
            'prefix'               => 'Prefix',
            'searchable'           => 'Searchable',
            'size'                 => 'Size',
            'sortable'             => 'Sortable',
            'suffix'               => 'Suffix',
            'toggleable'           => 'Toggleable',
            'tooltip'              => 'Tooltip',
            'verticalAlignment'    => 'Vertical Alignment',
            'verticallyAlignStart' => 'Vertically Align Start',
            'weight'               => 'Weight',
            'width'                => 'Width',
            'words'                => 'Words',
            'wrapHeader'           => 'Wrap Header',
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'date'            => 'Date',
                'dateTime'        => 'Date Time',
                'dateTimeTooltip' => 'Date Time Tooltip',
                'since'           => 'Since',
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }

    public static function getInfolistSettingsSchema(): array
    {
        return [
            Forms\Components\Repeater::make('infolist_settings')
                ->hiddenLabel()
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->searchable()
                        ->required()
                        ->distinct()
                        ->live()
                        ->options(fn(Forms\Get $get): array => static::getTypeInfolistSettings($get('../../type'))),
                    Forms\Components\TextInput::make('value')
                        ->label('Value')
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
                        ->label('Color')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'color',
                            'iconColor',
                            'hintColor',
                            'trueColor',
                            'falseColor',
                        ]))
                        ->options([
                            'danger'    => 'Danger',
                            'info'      => 'Info',
                            'primary'   => 'Primary',
                            'secondary' => 'Secondary',
                            'warning'   => 'Warning',
                            'success'   => 'Success',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Font Weight')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'weight',
                        ]))
                        ->options([
                            FontWeight::Thin->name       => 'Thin',
                            FontWeight::ExtraLight->name => 'Extra Light',
                            FontWeight::Light->name      => 'Light',
                            FontWeight::Normal->name     => 'Normal',
                            FontWeight::Medium->name     => 'Medium',
                            FontWeight::SemiBold->name   => 'Semi Bold',
                            FontWeight::Bold->name       => 'Bold',
                            FontWeight::ExtraBold->name  => 'Extra Bold',
                            FontWeight::Black->name      => 'Black',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Icon Position')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'iconPosition',
                        ]))
                        ->options([
                            IconPosition::Before->value => 'Before',
                            IconPosition::After->value  => 'After',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Size')
                        ->required()
                        ->visible(fn(Forms\Get $get): bool => in_array($get('setting'), [
                            'size',
                        ]))
                        ->options([
                            TextColumn\TextColumnSize::Small->name  => 'Small',
                            TextColumn\TextColumnSize::Medium->name => 'Medium',
                            TextColumn\TextColumnSize::Large->name  => 'Large',
                        ]),

                    Forms\Components\TextInput::make('value')
                        ->label('Value')
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
                ->addActionLabel('Add Setting')
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
            'badge'               => 'Badge',
            'color'               => 'Color', // TODO:
            'copyable'            => 'Copyable',
            'copyMessage'         => 'Copy Message',
            'copyMessageDuration' => 'Copy Message Duration',
            'default'             => 'Default',
            'icon'                => 'Icon',
            'iconColor'           => 'Icon Color',
            'iconPosition'        => 'Icon Position',
            'label'               => 'Label',
            'limit'               => 'Limit',
            'lineClamp'           => 'Line Clamp',
            'money'               => 'Money',
            'placeholder'         => 'Placeholder',
            'size'                => 'Size',
            'tooltip'             => 'Tooltip',
            'weight'              => 'Weight',
            'words'               => 'Words',
            'columnSpan'          => 'Column Span',
            'helperText'          => 'Helper Text',
            'hint'                => 'Hint',
            'hintColor'           => 'Hint Color',
            'hintIcon'            => 'Hint Icon',
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'date'            => 'Date',
                'dateTime'        => 'Date Time',
                'dateTimeTooltip' => 'Date Time Tooltip',
                'since'           => 'Since',
            ],

            'checkbox_list' => [
                'separator'             => 'Separator',
                'listWithLineBreaks'    => 'List with Line Breaks',
                'bulleted'              => 'Bulleted',
                'limitList'             => 'Limit List',
                'expandableLimitedList' => 'Expandable Limited List',
            ],

            'select' => [
                'separator'             => 'Separator',
                'listWithLineBreaks'    => 'List with Line Breaks',
                'bulleted'              => 'Bulleted',
                'limitList'             => 'Limit List',
                'expandableLimitedList' => 'Expandable Limited List',
            ],

            'checkbox' => [
                'boolean'    => 'Boolean',
                'falseIcon'  => 'False Icon',
                'trueIcon'   => 'True Icon',
                'trueColor'  => 'True Color',
                'falseColor' => 'False Color',
            ],

            'toggle' => [
                'boolean'    => 'Boolean',
                'falseIcon'  => 'False Icon',
                'trueIcon'   => 'True Icon',
                'trueColor'  => 'True Color',
                'falseColor' => 'False Color',
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }
}
