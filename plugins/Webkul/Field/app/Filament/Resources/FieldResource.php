<?php

namespace Webkul\Field\Filament\Resources;

use Webkul\Field\Filament\Resources\FieldResource\Pages;
use Webkul\Field\Filament\Resources\FieldResource\RelationManagers;
use Webkul\Field\Models\Field;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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
                                    ->email()
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
                                'checkbox',
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
                                    ->required()
                                    ->disabledOn('edit')
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
                                    ])
                                    ->searchable()
                                    ->native(false)
                                    ->live(),
                                Forms\Components\Select::make('input_type')
                                    ->required()
                                    ->disabledOn('edit')
                                    ->options([
                                        'text' => 'Text',
                                        'email' => 'Email',
                                        'numeric' => 'Numeric',
                                        'integer' => 'Integer',
                                        'password' => 'Password',
                                        'tel' => 'Telephone',
                                        'url' => 'URL',
                                        'color' => 'Color',
                                    ])
                                    ->native(false)
                                    ->visible(fn (Forms\Get $get): bool => $get('type') == 'text'),
                                Forms\Components\TextInput::make('sort_order')
                                    ->integer()
                                    ->required()
                                    ->maxLength(255),
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
                                ->options(fn (Forms\Get $get): array => static::getTypeFormValidations($get('../../../type')))
                                ->searchable()
                                ->required()
                                ->live(),
                            Forms\Components\TextInput::make('field')
                                ->label('Field')
                                ->visible(fn (Forms\Get $get): bool => in_array($get('validation'), [
                                    'prohibitedIf',
                                    'prohibitedUnless',
                                    'requiredIf',
                                    'requiredUnless',
                                ]))
                                ->required(),
                            Forms\Components\TextInput::make('value')
                                ->label('Value / Field')
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
                                    'multipleOf',
                                    'minSize',
                                    'maxSize',
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
                                ]))
                                ->required(),
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
                                ->options(fn (Forms\Get $get): array => static::getTypeFormSettings($get('../../../type')))
                                ->required()
                                ->searchable()
                                ->preload()
                                ->live(),
                            Forms\Components\TextInput::make('value')
                                ->label('Value')
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'id',
                                    'default',
                                    'helperText',
                                    'hint',
                                    'hintIcon',
                                    'placeholder',
                                    'autocomplete',
                                    'autocapitalize',
                                    'prefix',
                                    'prefixIcon',
                                    'suffix',
                                    'suffixIcon',
                                    'mask',
                                    'loadingMessage',
                                    'noSearchResultsMessage',
                                    'searchPrompt',
                                    'searchingMessage',
                                    'onIcon',
                                    'offIcon',
                                    'format',
                                    'displayFormat',
                                    'timezone',
                                    'locale',
                                    'disabledDates',
                                ]))
                                ->required(),
                            Forms\Components\TextInput::make('value')
                                ->label('Value')
                                ->numeric()
                                ->minValue(0)
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'rows',
                                    'cols',
                                    'columns',
                                    'searchDebounce',
                                    'step',
                                    'optionsLimit',
                                    'minItems',
                                    'maxItems',
                                    'firstDayOfWeek',
                                    'hoursStep',
                                    'minutesStep',
                                    'secondsStep',
                                    'seconds',
                                ]))
                                ->required(),
                            Forms\Components\Select::make('value')
                                ->label('Color')
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
                                ])
                                ->required(),
                            Forms\Components\Select::make('value')
                                ->label('Color')
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'gridDirection',
                                ]))
                                ->options([
                                    'row' => 'Row',
                                    'column' => 'Column',
                                ])
                                ->required(),
                            Forms\Components\Toggle::make('value')
                                ->label('Value')
                                ->visible(fn (Forms\Get $get): bool => in_array($get('setting'), [
                                    'native',
                                ]))
                                ->inline(false)
                                ->required(),
                            Forms\Components\Select::make('value')
                                ->label('Value')
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
                                ])
                                ->required(),
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

    public static function getTableSettingsSchema(): array
    {
        return [
            Forms\Components\Repeater::make('table_settings')
                ->hiddenLabel()
                ->schema([
                    Forms\Components\Select::make('setting')
                        ->options(fn (Forms\Get $get): array => static::getTypeTableSettings($get('../../type')))
                        ->searchable()
                        ->required()
                        ->live(),
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

    public static function getInfolistConfigurationSchema(): array
    {
        return [];
    }

    public static function getTypeFormValidations($type): array
    {
        if (is_null($type)) {
            return [];
        }
        
        $commonValidations = [
            'nullable' => 'Nullable',
            'prohibited' => 'Prohibited',
            'prohibitedIf' => 'Prohibited If',
            'prohibitedUnless' => 'Prohibited Unless',
            'prohibits' => 'Prohibits',
            'required' => 'Required',
            'requiredUnless' => 'Required Unless',
            'requiredWith' => 'Required With',
            'requiredWithAll' => 'Required With All',
            'requiredWithout' => 'Required Without',
            'requiredWithoutAll' => 'Required Without All',
            'rules' => 'Custom Rules',
            'unique' => 'Unique',
            'gt' => 'Greater Than',
            'gte' => 'Greater Than or Equal',
            'lt' => 'Less Than',
            'lte' => 'Less Than or Equal',
            'multipleOf' => 'Multiple Of',
            'minSize' => 'Min Size',
            'maxSize' => 'Max Size',
        ];
        
        $typeValidations = match ($type) {
            'text' => [
                'alphaNum' => 'After Number',
                'alphaDash' => 'Alpha Dash',
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
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
                'filled' => 'Filled',
            ],

            'select' => [
                'exists' => 'Exists',
                'in' => 'In',
                'notIn' => 'Not In',
                'different' => 'Different',
                'same' => 'Same',
            ],

            'radio' => [
            ],

            'checkbox' => [
                'accepted' => 'Accepted',
                'declined' => 'Declined',
                'required' => 'Required',
                'requiredIf' => 'Required If',
                'requiredIfAccepted' => 'Required If Accepted',
            ],

            'toggle' => [
                'accepted' => 'Accepted',
                'declined' => 'Declined',
                'required' => 'Required',
            ],

            'checkbox_list' => [
                'maxItems' => 'Max Items',
                'minItems' => 'Min Items',
                'required' => 'Required',
                'in' => 'In',
            ],

            'datetime' => [
                'after' => 'After',
                'afterOrEqual' => 'After or Equal',
                'before' => 'Before',
                'beforeOrEqual' => 'Before or Equal',
            ],

            'editor' => [
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
                'filled' => 'Filled',
            ],

            'markdown' => [
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
                'filled' => 'Filled',
            ],

            'color' => [
                'hexColor' => 'Hex Color',
            ],

            default => [],
        };

        return array_merge($typeValidations, $commonValidations);
    }

    public static function getTypeFormSettings($type): array
    {
        if (is_null($type)) {
            return [];
        }

        return match($type) {
            'text' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'autofocus' => 'Autofocus',
                'placeholder' => 'Placeholder',
                'inputMode' => 'Input Mode',
                'step' => 'Step',
                'autocomplete' => 'Autocomplete',
                'autocapitalize' => 'Autocapitalize',
                'prefix' => 'Prefix',
                'prefixIcon' => 'Prefix Icon',
                'prefixIconColor' => 'Prefix Icon Color',
                'suffix' => 'Suffix',
                'suffixIcon' => 'Suffix Icon',
                'suffixIconColor' => 'Suffix Icon Color',
                'mask' => 'Mask',
                'readOnly' => 'Read Only',
            ],

            'textarea' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'autofocus' => 'Autofocus',
                'placeholder' => 'Placeholder',
                'rows' => 'Rows',
                'cols' => 'Columns',
                'autosize' => 'Autosize',
                'readOnly' => 'Read Only',
            ],

            'select' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'searchable' => 'Searchable',
                'multiple' => 'Multiple',
                'native' => 'Native',
                'preload' => 'Preload',
                'loadingMessage' => 'Loading Message',
                'noSearchResultsMessage' => 'No Search Results Message',
                'searchPrompt' => 'Search Prompt',
                'searchingMessage' => 'Searching Message',
                'searchDebounce' => 'Search Debounce',
                'optionsLimit' => 'Options Limit',
            ],

            'radio' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
            ],

            'checkbox' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'inline' => 'Inline',
            ],

            'toggle' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'onIcon' => 'On Icon',
                'offIcon' => 'Off Icon',
                'onColor' => 'On Color',
                'offColor' => 'Off Color',
            ],

            'checkbox_list' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'columns' => 'Columns',
                'gridDirection' => 'Grid Direction',
                'bulkToggleable' => 'Bulk Toggleable',
                'searchable' => 'Searchable',
                'noSearchResultsMessage' => 'No Search Results Message',
                'minItems' => 'Min Items',
                'maxItems' => 'Max Items',
            ],

            'datetime' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'format' => 'Format',
                'displayFormat' => 'Display Format',
                'timezone' => 'Timezone',
                'locale' => 'Locale',
                'firstDayOfWeek' => 'First Day of Week',
                'weekStartsOnMonday' => 'Week Starts on Monday',
                'weekStartsOnSunday' => 'Week Starts on Sunday',
                'disabledDates' => 'Disabled Dates',
                'closeOnDateSelection' => 'Close on Date Selection',
                'hoursStep' => 'Hours Step',
                'minutesStep' => 'Minutes Step',
                'secondsStep' => 'Seconds Step',
                'seconds' => 'Seconds',
            ],

            'editor' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'placeholder' => 'Placeholder',
                'readOnly' => 'Read Only',
            ],

            'markdown' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'placeholder' => 'Placeholder',
                'readOnly' => 'Read Only',
            ],

            'color' => [
                'id' => 'Id',
                'default' => 'Default Value',
                'helperText' => 'Helper Text',
                'hint' => 'Hint',
                'hintColor' => 'Hint Color',
                'hintIcon' => 'Hint Icon',
                'disabled' => 'Disabled',
                'hsl' => 'HSL',
                'rgb' => 'RGB',
                'rgba' => 'RGBA',
            ],

            // File-specific settings that weren't clearly associated with the given field types
            'file' => [
                'directory' => 'Directory',
                'visibility' => 'Visibility',
                'image' => 'Image',
                'imageEditor' => 'Image Editor',
                'imageEditorAspectRatios' => 'Image Editor Aspect Ratios',
                'imageEditorMode' => 'Image Editor Mode',
                'imageEditorEmptyFillColor' => 'Image Editor Empty Fill Color',
                'imageResizeMode' => 'Image Resize Mode',
                'imageCropAspectRatio' => 'Image Crop Aspect Ratio',
                'imageResizeTargetWidth' => 'Image Resize Target Width',
                'imageResizeTargetHeight' => 'Image Resize Target Height',
                'imagePreviewHeight' => 'Image Preview Height',
                'loadingIndicatorPosition' => 'Loading Indicator Position',
                'panelAspectRatio' => 'Panel Aspect Ratio',
                'panelLayout' => 'Panel Layout',
                'removeUploadedFileButtonPosition' => 'Remove Uploaded File Button Position',
                'uploadButtonPosition' => 'Upload Button Position',
                'uploadProgressIndicatorPosition' => 'Upload Progress Indicator Position',
                'reorderable' => 'Reorderable',
                'appendFiles' => 'Append Files',
                'openable' => 'Openable',
                'downloadable' => 'Downloadable',
                'previewable' => 'Previewable',
                'moveFiles' => 'Move Files',
                'storeFiles' => 'Store Files',
                'orientImagesFromExif' => 'Orient Images from EXIF',
                'deletable' => 'Deletable',
                'fetchFileInformation' => 'Fetch File Information',
                'uploadingMessage' => 'Uploading Message',
                'acceptedFileTypes' => 'Accepted File Types',
                'fileAttachmentsDirectory' => 'File Attachments Directory',
                'fileAttachmentsVisibility' => 'File Attachments Visibility',
            ],
        };
    }

    public static function getTypeTableSettings($type): array
    {
        if (is_null($type)) {
            return [];
        }

        $commonSettings = [
            'searchable' => 'Searchable',
            'filterable' => 'Filterable',
            'sortable' => 'Sortable',
            'groupable' => 'Groupable',
            'label' => 'Label',
            'default' => 'Default',
            'placeholder' => 'Placeholder',
            'toggleable' => 'Toggleable',
            'tooltip' => 'Tooltip',
            'alignment' => 'Alignment',
            'alignEnd' => 'Align End',
            'alignStart' => 'Align Start',
            'verticalAlignment' => 'Vertical Alignment',
            'verticallyAlignStart' => 'Vertically Align Start',
            'wrapHeader' => 'Wrap Header',
            'grow' => 'Grow',
            'boolean' => 'Boolean',
            'width' => 'Width',
            'badge' => 'Badge',
            'color' => 'Color',
            'money' => 'Money',
            'limit' => 'Limit',
            'words' => 'Words',
            'lineClamp' => 'Line Clamp',
            'prefix' => 'Prefix',
            'suffix' => 'Suffix',
            'icon' => 'Icon',
            'iconPosition' => 'Icon Position',
            'iconColor' => 'Icon Color',
            'size' => 'Size',
            'weight' => 'Weight',
            'copyable' => 'Copyable',
            'copyMessage' => 'Copy Message',
            'copyMessageDuration' => 'Copy Message Duration',
        ];

        $typeSettings = match ($type) {
            'datetime' => [
                'dateTime' => 'Date Time',
                'since' => 'Since',
                'dateTimeTooltip' => 'Date Time Tooltip',
            ],

            default => [],
        };


        return array_merge($typeSettings, $commonSettings);
    }
}
