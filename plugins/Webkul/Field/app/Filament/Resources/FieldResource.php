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
use Webkul\Field\Filament\Resources\FieldResource\Pages;
use Webkul\Field\Models\Field;

class FieldResource extends Resource
{
    protected static ?string $model = Field::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Custom Fields';

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->maxLength(255)
                                    ->disabledOn('edit'),
                            ])
                            ->columns(2),

                        Forms\Components\Section::make('Options')
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
                                    ->addActionLabel('Add Option'),
                            ]),

                        Forms\Components\Section::make('Form Settings')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema(static::getFormSettingsSchema())
                                    ->statePath('form_settings'),
                            ]),

                        Forms\Components\Section::make('Table Settings')
                            ->schema(static::getTableSettingsSchema()),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Settings')
                            ->schema([
                                Forms\Components\Select::make('type')
                                    ->label('Type')
                                    ->required()
                                    ->disabledOn('edit')
                                    ->searchable()
                                    ->native(false)
                                    ->live()
                                    ->options([
                                        'text' => 'Text Input',
                                        'textarea' => 'Textarea',
                                        'select' => 'Select',
                                        'checkbox' => 'Checkbox',
                                        'radio' => 'Radio',
                                        'toggle' => 'Toggle',
                                        'checkbox_list' => 'Checkbox List',
                                        'datetime' => 'Date Time Picker',
                                        'editor' => 'Rich Text Editor',
                                        'markdown' => 'Markdown Editor',
                                        'color' => 'Color Picker',
                                    ]),
                                Forms\Components\Select::make('input_type')
                                    ->label('Input Type')
                                    ->required()
                                    ->disabledOn('edit')
                                    ->native(false)
                                    ->visible(fn (Forms\Get $get): bool => $get('type') == 'text')
                                    ->options([
                                        'text' => 'Text',
                                        'email' => 'Email',
                                        'numeric' => 'Numeric',
                                        'integer' => 'Integer',
                                        'password' => 'Password',
                                        'tel' => 'Telephone',
                                        'url' => 'URL',
                                        'color' => 'Color',
                                    ]),
                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->required()
                                    ->integer()
                                    ->maxLength(255),
                            ]),

                        Forms\Components\Section::make('Resource')
                            ->schema([
                                Forms\Components\Select::make('customizable_type')
                                    ->label('Resource')
                                    ->required()
                                    ->searchable()
                                    ->native(false)
                                    ->disabledOn('edit')
                                    ->options(fn () => collect(Filament::getResources())->filter(fn ($resource) => $resource !== self::class)->mapWithKeys(fn ($resource) => [
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
                    ->label('Code')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type'),
                Tables\Columns\TextColumn::make('customizable_type')
                    ->label('Resource')
                    ->description(fn (Field $record): string => str($record->customizable_type)->afterLast('\\')->toString().'Resource'),
                Tables\Columns\TextColumn::make('created_at'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'text' => 'Text Input',
                        'textarea' => 'Textarea',
                        'select' => 'Select',
                        'checkbox' => 'Checkbox',
                        'radio' => 'Radio',
                        'toggle' => 'Toggle',
                        'checkbox_list' => 'Checkbox List',
                        'datetime' => 'Date Time Picker',
                        'editor' => 'Rich Text Editor',
                        'markdown' => 'Markdown Editor',
                        'color' => 'Color Picker',
                    ]),
                Tables\Filters\SelectFilter::make('customizable_type')
                    ->label('Resource')
                    ->options(fn () => collect(Filament::getResources())->filter(fn ($resource) => $resource !== self::class)->mapWithKeys(fn ($resource) => [
                        $resource::getModel() => str($resource)->afterLast('\\')->toString(),
                    ])),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFields::route('/'),
            'create' => Pages\CreateField::route('/create'),
            'edit' => Pages\EditField::route('/{record}/edit'),
        ];
    }

    public static function getFormSettingsSchema(): array
    {
        return [
            Forms\Components\Fieldset::make('Validations')
                ->schema([
                    Forms\Components\Repeater::make('validations')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Select::make('validation')
                                ->label('validation')
                                ->searchable()
                                ->required()
                                ->live()
                                ->options(fn (Forms\Get $get): array => static::getTypeFormValidations($get('../../../type'))),
                            Forms\Components\TextInput::make('field')
                                ->label('Field')
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('validation'), [
                                    'prohibitedIf',
                                    'prohibitedUnless',
                                    'requiredIf',
                                    'requiredUnless',
                                ])),
                            Forms\Components\TextInput::make('value')
                                ->label('Value / Field')
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('validation'), [
                                    'after',
                                    'afterOrEqual',
                                    'before',
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
                                    'requiredIf',
                                    'requiredIf',
                                    'requiredIfAccepted',
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
                        ->addActionLabel('Add Option')
                        ->columns(3)
                        ->collapsible()
                        ->itemLabel(function (array $state, Forms\Get $get): ?string {
                            $validations = static::getTypeFormValidations($get('../type'));

                            return $validations[$state['validation']] ?? null;
                        }),
                ])
                ->columns(1),

            Forms\Components\Fieldset::make('Additional Settings')
                ->schema([
                    Forms\Components\Repeater::make('settings')
                        ->hiddenLabel()
                        ->schema([
                            Forms\Components\Select::make('setting')
                                ->label('Setting')
                                ->required()
                                ->searchable()
                                ->live()
                                ->options(fn (Forms\Get $get): array => static::getTypeFormSettings($get('../../../type'))),
                            Forms\Components\TextInput::make('value')
                                ->label('Value')
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
                                ->label('Value')
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
                                ->label('Color')
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'hintColor',
                                    'prefixIconColor',
                                    'suffixIconColor',
                                    'onColor',
                                    'offColor',
                                ]))
                                ->options([
                                    'danger' => 'Danger',
                                    'info' => 'Info',
                                    'primary' => 'Primary',
                                    'secondary' => 'Secondary',
                                    'warning' => 'Warning',
                                    'success' => 'Success',
                                ]),
                            Forms\Components\Select::make('value')
                                ->label('Value')
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'gridDirection',
                                ]))
                                ->options([
                                    'row' => 'Row',
                                    'column' => 'Column',
                                ]),
                            Forms\Components\Toggle::make('value')
                                ->label('Value')
                                ->required()
                                ->inline(false)
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'native',
                                ])),
                            Forms\Components\Select::make('value')
                                ->label('Value')
                                ->required()
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'inputMode',
                                ]))
                                ->options([
                                    'none' => 'None',
                                    'text' => 'Text',
                                    'numeric' => 'Numeric',
                                    'decimal' => 'Decimal',
                                    'tel' => 'Tel',
                                    'search' => 'Search',
                                    'email' => 'Email',
                                    'url' => 'URL',
                                ]),
                        ])
                        ->addActionLabel('Add Setting')
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
            'gt' => 'Greater Than',
            'gte' => 'Greater Than or Equal',
            'lt' => 'Less Than',
            'lte' => 'Less Than or Equal',
            'maxSize' => 'Max Size',
            'minSize' => 'Min Size',
            'multipleOf' => 'Multiple Of',
            'nullable' => 'Nullable',
            'prohibited' => 'Prohibited',
            'prohibitedIf' => 'Prohibited If',
            'prohibitedUnless' => 'Prohibited Unless',
            'prohibits' => 'Prohibits',
            'required' => 'Required',
            'requiredIf' => 'Required If',
            'requiredIfAccepted' => 'Required If Accepted',
            'requiredUnless' => 'Required Unless',
            'requiredWith' => 'Required With',
            'requiredWithAll' => 'Required With All',
            'requiredWithout' => 'Required Without',
            'requiredWithoutAll' => 'Required Without All',
            'rules' => 'Custom Rules',
            'unique' => 'Unique',
        ];

        $typeValidations = match ($type) {
            'text' => [
                'alphaDash' => 'Alpha Dash',
                'alphaNum' => 'After Number',
                'ascii' => 'Ascii',
                'doesntEndWith' => 'Doesn\'t End With',
                'doesntStartWith' => 'Doesn\'t Start With',
                'endsWith' => 'Ends With',
                'filled' => 'Filled',
                'ip' => 'IP',
                'ipv4' => 'IPv4',
                'ipv6' => 'IPv6',
                'length' => 'Length',
                'macAddress' => 'MAC Address',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
                'regex' => 'Regex',
                'startsWith' => 'Starts With',
                'ulid' => 'ULID',
                'uuid' => 'UUID',
            ],

            'textarea' => [
                'filled' => 'Filled',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
            ],

            'select' => [
                'different' => 'Different',
                'exists' => 'Exists',
                'in' => 'In',
                'notIn' => 'Not In',
                'same' => 'Same',
            ],

            'radio' => [
            ],

            'checkbox' => [
                'accepted' => 'Accepted',
                'declined' => 'Declined',
            ],

            'toggle' => [
                'accepted' => 'Accepted',
                'declined' => 'Declined',
            ],

            'checkbox_list' => [
                'in' => 'In',
                'maxItems' => 'Max Items',
                'minItems' => 'Min Items',
            ],

            'datetime' => [
                'after' => 'After',
                'afterOrEqual' => 'After or Equal',
                'before' => 'Before',
                'beforeOrEqual' => 'Before or Equal',
            ],

            'editor' => [
                'filled' => 'Filled',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
            ],

            'markdown' => [
                'filled' => 'Filled',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
            ],

            'color' => [
                'hexColor' => 'Hex Color',
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
                'autocapitalize' => 'Autocapitalize',
                'autocomplete' => 'Autocomplete',
                'autofocus' => 'Autofocus',
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'inputMode' => 'Input Mode',
                'mask' => 'Mask',
                'placeholder' => 'Placeholder',
                'prefix' => 'Prefix',
                'prefixIcon' => 'Prefix Icon',
                'prefixIconColor' => 'Prefix Icon Color',
                'readOnly' => 'Read Only',
                'step' => 'Step',
                'suffix' => 'Suffix',
                'suffixIcon' => 'Suffix Icon',
                'suffixIconColor' => 'Suffix Icon Color',
            ],

            'textarea' => [
                'autofocus' => 'Autofocus',
                'autosize' => 'Autosize',
                'cols' => 'Columns',
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'placeholder' => 'Placeholder',
                'readOnly' => 'Read Only',
                'rows' => 'Rows',
            ],

            'select' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'loadingMessage' => 'Loading Message',
                'multiple' => 'Multiple',
                'native' => 'Native',
                'noSearchResultsMessage' => 'No Search Results Message',
                'optionsLimit' => 'Options Limit',
                'preload' => 'Preload',
                'searchable' => 'Searchable',
                'searchDebounce' => 'Search Debounce',
                'searchingMessage' => 'Searching Message',
                'searchPrompt' => 'Search Prompt',
            ],

            'radio' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
            ],

            'checkbox' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'inline' => 'Inline',
            ],

            'toggle' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'offColor' => 'Off Color',
                'offIcon' => 'Off Icon',
                'onColor' => 'On Color',
                'onIcon' => 'On Icon',
            ],

            'checkbox_list' => [
                'bulkToggleable' => 'Bulk Toggleable',
                'columns' => 'Columns',
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'gridDirection' => 'Grid Direction',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'maxItems' => 'Max Items',
                'minItems' => 'Min Items',
                'noSearchResultsMessage' => 'No Search Results Message',
                'searchable' => 'Searchable',
            ],

            'datetime' => [
                'closeOnDateSelection' => 'Close on Date Selection',
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'disabledDates' => 'Disabled Dates',
                'displayFormat' => 'Display Format',
                'firstDayOfWeek' => 'First Day of Week',
                'format' => 'Format',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'hoursStep' => 'Hours Step',
                'id' => 'Id',
                'locale' => 'Locale',
                'minutesStep' => 'Minutes Step',
                'native' => 'Native',
                'seconds' => 'Seconds',
                'secondsStep' => 'Seconds Step',
                'timezone' => 'Timezone',
                'weekStartsOnMonday' => 'Week Starts on Monday',
                'weekStartsOnSunday' => 'Week Starts on Sunday',
            ],

            'editor' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'placeholder' => 'Placeholder',
                'readOnly' => 'Read Only',
            ],

            'markdown' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'id' => 'Id',
                'placeholder' => 'Placeholder',
                'readOnly' => 'Read Only',
            ],

            'color' => [
                'default' => 'Default Value',
                'disabled' => 'Disabled',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'hsl' => 'HSL',
                'id' => 'Id',
                'rgb' => 'RGB',
                'rgba' => 'RGBA',
            ],

            // File-specific settings that weren't clearly associated with the given field types
            'file' => [
                'acceptedFileTypes' => 'Accepted File Types',
                'appendFiles' => 'Append Files',
                'deletable' => 'Deletable',
                'directory' => 'Directory',
                'downloadable' => 'Downloadable',
                'fetchFileInformation' => 'Fetch File Information',
                'fileAttachmentsDirectory' => 'File Attachments Directory',
                'fileAttachmentsVisibility' => 'File Attachments Visibility',
                'image' => 'Image',
                'imageCropAspectRatio' => 'Image Crop Aspect Ratio',
                'imageEditor' => 'Image Editor',
                'imageEditorAspectRatios' => 'Image Editor Aspect Ratios',
                'imageEditorEmptyFillColor' => 'Image Editor Empty Fill Color',
                'imageEditorMode' => 'Image Editor Mode',
                'imagePreviewHeight' => 'Image Preview Height',
                'imageResizeMode' => 'Image Resize Mode',
                'imageResizeTargetHeight' => 'Image Resize Target Height',
                'imageResizeTargetWidth' => 'Image Resize Target Width',
                'loadingIndicatorPosition' => 'Loading Indicator Position',
                'moveFiles' => 'Move Files',
                'openable' => 'Openable',
                'orientImagesFromExif' => 'Orient Images from EXIF',
                'panelAspectRatio' => 'Panel Aspect Ratio',
                'panelLayout' => 'Panel Layout',
                'previewable' => 'Previewable',
                'removeUploadedFileButtonPosition' => 'Remove Uploaded File Button Position',
                'reorderable' => 'Reorderable',
                'storeFiles' => 'Store Files',
                'uploadButtonPosition' => 'Upload Button Position',
                'uploadingMessage' => 'Uploading Message',
                'uploadProgressIndicatorPosition' => 'Upload Progress Indicator Position',
                'visibility' => 'Visibility',
            ],
        };
    }

    public static function getTableSettingsSchema(): array
    {
        return [
            Forms\Components\Toggle::make('use_in_table')
                ->required()
                ->live(),
            Forms\Components\Repeater::make('table_settings')
                ->hiddenLabel()
                ->visible(fn (Forms\Get $get): bool => $get('use_in_table'))
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->searchable()
                        ->required()
                        ->live()
                        ->options(fn (Forms\Get $get): array => static::getTypeTableSettings($get('../../type'))),
                    Forms\Components\TextInput::make('value')
                        ->label('Value')
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
                        ->label('Color')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'iconColor',
                        ]))
                        ->options([
                            'danger' => 'Danger',
                            'info' => 'Info',
                            'primary' => 'Primary',
                            'secondary' => 'Secondary',
                            'warning' => 'Warning',
                            'success' => 'Success',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Alignment')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'alignment',
                            'verticalAlignment',
                        ]))
                        ->options([
                            Alignment::Start->value => 'Start',
                            Alignment::Left->value => 'Left',
                            Alignment::Center->value => 'Center',
                            Alignment::End->value => 'End',
                            Alignment::Right->value => 'Right',
                            Alignment::Justify->value => 'Justify',
                            Alignment::Between->value => 'Between',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Font Weight')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'weight',
                        ]))
                        ->options([
                            FontWeight::Thin->name => 'Thin',
                            FontWeight::ExtraLight->name => 'Extra Light',
                            FontWeight::Light->name => 'Light',
                            FontWeight::Normal->name => 'Normal',
                            FontWeight::Medium->name => 'Medium',
                            FontWeight::SemiBold->name => 'Semi Bold',
                            FontWeight::Bold->name => 'Bold',
                            FontWeight::ExtraBold->name => 'Extra Bold',
                            FontWeight::Black->name => 'Black',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Icon Position')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'iconPosition',
                        ]))
                        ->options([
                            IconPosition::Before->value => 'Before',
                            IconPosition::After->value => 'After',
                        ]),

                    Forms\Components\Select::make('value')
                        ->label('Size')
                        ->required()
                        ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                            'size',
                        ]))
                        ->options([
                            TextColumn\TextColumnSize::Small->name => 'Small',
                            TextColumn\TextColumnSize::Medium->name => 'Medium',
                            TextColumn\TextColumnSize::Large->name => 'Large',
                        ]),

                    Forms\Components\TextInput::make('value')
                        ->label('Value')
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
                ->addActionLabel('Add Setting')
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
            'alignEnd' => 'Align End',
            'alignment' => 'Alignment',
            'alignStart' => 'Align Start',
            'badge' => 'Badge',
            'boolean' => 'Boolean',
            'color' => 'Color', // TODO:
            'copyable' => 'Copyable',
            'copyMessage' => 'Copy Message',
            'copyMessageDuration' => 'Copy Message Duration',
            'default' => 'Default',
            'filterable' => 'Filterable',
            'groupable' => 'Groupable',
            'grow' => 'Grow',
            'icon' => 'Icon',
            'iconColor' => 'Icon Color',
            'iconPosition' => 'Icon Position',
            'label' => 'Label',
            'limit' => 'Limit',
            'lineClamp' => 'Line Clamp',
            'money' => 'Money',
            'placeholder' => 'Placeholder',
            'prefix' => 'Prefix',
            'searchable' => 'Searchable',
            'size' => 'Size',
            'sortable' => 'Sortable',
            'suffix' => 'Suffix',
            'toggleable' => 'Toggleable',
            'tooltip' => 'Tooltip',
            'verticalAlignment' => 'Vertical Alignment',
            'verticallyAlignStart' => 'Vertically Align Start',
            'weight' => 'Weight',
            'width' => 'Width',
            'words' => 'Words',
            'wrapHeader' => 'Wrap Header',
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'dateTime' => 'Date Time',
                'dateTimeTooltip' => 'Date Time Tooltip',
                'since' => 'Since',
            ],

            default => [],
        };

        return array_merge($typeSettings, $commonSettings);
    }

    public static function getInfolistSettingsSchema(): array
    {
        return [];
    }
}
