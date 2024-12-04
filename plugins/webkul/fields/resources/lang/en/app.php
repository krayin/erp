<?php

return [
    'navigation' => [
        'label' => 'Custom Fields',
        'group' => 'Settings'
    ],

    'model-label' => 'Fields',

    'resources' => [
        'pages' => [
            'list-records' => [
                'index' => [
                    'title' => 'Custom Fields'
                ]
            ]
        ]
    ],

    'form' => [
        'sections' => [
            'main' => 'Field Details',
            'options' => 'Options',
            'validations' => 'Validations',
            'form-settings' => 'Form Settings',
            'table-settings' => 'Table Settings',
            'infolist-settings' => 'Infolist Settings',
            'settings' => 'Settings',
            'resource' => 'Resource',
            'additional-settings' => 'Additional Settings',
        ],

        'fields' => [
            'setting' => 'Setting',
            'name' => 'Name',
            'validation' => 'Validation',
            'code' => 'Code',
            'type' => 'Type',
            'value' => 'Value',
            'row' => 'Row',
            'column' => 'Column',
            'field-input-types' => 'Input Type',
            'sort-order' => 'Sort Order',
            'resource' => 'Resource',
            'is-multiselect' => 'Allow Multiple Selection',
            'use-in-table'  => 'Use in table',
            'color' => 'Color',
            'alignment' => 'Alignment',
            'font-weight' => 'Font Weight',
            'icon-position' => 'Icon Position',
            'size' => 'Size',

            'types' => [
                'text' => 'Text Input',
                'textarea' => 'Textarea',
                'select' => 'Select',
                'checkbox' => 'Checkbox',
                'radio' => 'Radio',
                'toggle' => 'Toggle',
                'checkbox-list' => 'Checkbox List',
                'datetime' => 'Date Time Picker',
                'editor' => 'Rich Text Editor',
                'markdown' => 'Markdown Editor',
                'color' => 'Color Picker',
                'danger' => 'Danger',
                'info' => 'Info',
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'warning' => 'Warning',
                'success' => 'Success',
                'start'   => 'Start',
                'left'    => 'Left',
                'center'  => 'Center',
                'end' => 'End',
                'right' => 'Right',
                'justify' => 'Justify',
                'between' => 'Between',
                'thin' => 'Thin',
                'extra-light' => 'Extra Light',
                'light' => 'Light',
                'normal' => 'Normal',
                'medium' => 'Medium',
                'semi-bold' => 'Semi Bold',
                'bold' => 'Bold',
                'extra-bold' => 'Extra Bold',
                'black' => 'Black',
                'before' => 'Before',
                'after' => 'After',
                'small' => 'Small',
                'large' => 'Large',
            ],

            'input-types' => [
                'text' => 'Text',
                'email' => 'Email',
                'numeric' => 'Numeric',
                'integer' => 'Integer',
                'password' => 'Password',
                'tel' => 'Telephone',
                'url' => 'URL',
                'color' => 'Color',
                'none'  => 'None',
                'decimal' => 'Decimal',
                'search'  => 'Search',
                'url' => 'URL',
            ]
        ],

        'actions' => [
            'add-option' => 'Add Option',
            'add-setting' => 'Add Setting',
        ],

        'validations' => [
            'common' => [
                'gt'                 => 'Greater Than',
                'gte'                => 'Greater Than or Equal',
                'lt'                 => 'Less Than',
                'lte'                => 'Less Than or Equal',
                'maxSize'            => 'Max Size',
                'minSize'            => 'Min Size',
                'multipleOf'         => 'Multiple Of',
                'nullable'           => 'Nullable',
                'prohibited'         => 'Prohibited',
                'prohibitedIf'       => 'Prohibited If',
                'prohibitedUnless'   => 'Prohibited Unless',
                'prohibits'          => 'Prohibits',
                'required'           => 'Required',
                'requiredIf'         => 'Required If',
                'requiredIfAccepted' => 'Required If Accepted',
                'requiredUnless'     => 'Required Unless',
                'requiredWith'       => 'Required With',
                'requiredWithAll'    => 'Required With All',
                'requiredWithout'    => 'Required Without',
                'requiredWithoutAll' => 'Required Without All',
                'rules'              => 'Custom Rules',
                'unique'             => 'Unique',
            ],

            'text' => [
                'alphaDash'       => 'Alpha Dash',
                'alphaNum'        => 'Alpha Numeric',
                'ascii'           => 'ASCII',
                'doesntEndWith'   => "Doesn't End With",
                'doesntStartWith' => "Doesn't Start With",
                'endsWith'        => 'Ends With',
                'filled'          => 'Filled',
                'ip'              => 'IP',
                'ipv4'            => 'IPv4',
                'ipv6'            => 'IPv6',
                'length'          => 'Length',
                'macAddress'      => 'MAC Address',
                'maxLength'       => 'Max Length',
                'minLength'       => 'Min Length',
                'regex'           => 'Regex',
                'startsWith'      => 'Starts With',
                'ulid'            => 'ULID',
                'uuid'            => 'UUID',
            ],

            'textarea' => [
                'filled'    => 'Filled',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
            ],

            'select' => [
                'different' => 'Different',
                'exists'    => 'Exists',
                'in'        => 'In',
                'notIn'     => 'Not In',
                'same'      => 'Same',
            ],

            'radio' => [],

            'checkbox' => [
                'accepted' => 'Accepted',
                'declined' => 'Declined',
            ],

            'toggle' => [
                'accepted' => 'Accepted',
                'declined' => 'Declined',
            ],

            'checkbox-list' => [
                'in'       => 'In',
                'maxItems' => 'Max Items',
                'minItems' => 'Min Items',
            ],

            'datetime' => [
                'after'         => 'After',
                'afterOrEqual'  => 'After or Equal',
                'before'        => 'Before',
                'beforeOrEqual' => 'Before or Equal',
            ],

            'editor' => [
                'filled'    => 'Filled',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
            ],

            'markdown' => [
                'filled'    => 'Filled',
                'maxLength' => 'Max Length',
                'minLength' => 'Min Length',
            ],

            'color' => [
                'hexColor' => 'Hex Color',
            ],
        ],

        'settings' => [
            'text' => [
                'autocapitalize'  => 'Autocapitalize',
                'autocomplete'    => 'Autocomplete',
                'autofocus'       => 'Autofocus',
                'default'         => 'Default Value',
                'disabled'        => 'Disabled',
                'helperText'      => 'Helper Text',
                'hint'            => 'Hint',
                'hintColor'       => 'Hint Color',
                'hintIcon'        => 'Hint Icon',
                'id'              => 'Id',
                'inputMode'       => 'Input Mode',
                'mask'            => 'Mask',
                'placeholder'     => 'Placeholder',
                'prefix'          => 'Prefix',
                'prefixIcon'      => 'Prefix Icon',
                'prefixIconColor' => 'Prefix Icon Color',
                'readOnly'        => 'Read Only',
                'step'            => 'Step',
                'suffix'          => 'Suffix',
                'suffixIcon'      => 'Suffix Icon',
                'suffixIconColor' => 'Suffix Icon Color',
            ],

            'textarea' => [
                'autofocus'   => 'Autofocus',
                'autosize'    => 'Autosize',
                'cols'        => 'Columns',
                'default'     => 'Default Value',
                'disabled'    => 'Disabled',
                'helperText'  => 'Helper Text',
                'hint'        => 'Hint',
                'hintColor'   => 'Hint Color',
                'hintIcon'    => 'Hint Icon',
                'id'          => 'Id',
                'placeholder' => 'Placeholder',
                'readOnly'    => 'Read Only',
                'rows'        => 'Rows',
            ],

            'select' => [
                'default'        => 'Default Value',
                'disabled'       => 'Disabled',
                'helperText'     => 'Helper Text',
                'hint'           => 'Hint',
                'hintColor'      => 'Hint Color',
                'hintIcon'       => 'Hint Icon',
                'id'             => 'Id',
                'loadingMessage' => 'Loading Message',
                'noSearchResultsMessage' => 'No Search Results Message',
                'optionsLimit'           => 'Options Limit',
                'preload'                => 'Preload',
                'searchable'             => 'Searchable',
                'searchDebounce'         => 'Search Debounce',
                'searchingMessage'       => 'Searching Message',
                'searchPrompt'           => 'Search Prompt',
            ],

            'radio' => [
                'default'    => 'Default Value',
                'disabled'   => 'Disabled',
                'helperText' => 'Helper Text',
                'hint'       => 'Hint',
                'hintColor'  => 'Hint Color',
                'hintIcon'   => 'Hint Icon',
                'id'         => 'Id',
            ],

            'checkbox' => [
                'default'    => 'Default Value',
                'disabled'   => 'Disabled',
                'helperText' => 'Helper Text',
                'hint'       => 'Hint',
                'hintColor'  => 'Hint Color',
                'hintIcon'   => 'Hint Icon',
                'id'         => 'Id',
                'inline'     => 'Inline',
            ],

            'toggle' => [
                'default'    => 'Default Value',
                'disabled'   => 'Disabled',
                'helperText' => 'Helper Text',
                'hint'       => 'Hint',
                'hintColor'  => 'Hint Color',
                'hintIcon'   => 'Hint Icon',
                'id'         => 'Id',
                'offColor'   => 'Off Color',
                'offIcon'    => 'Off Icon',
                'onColor'    => 'On Color',
                'onIcon'     => 'On Icon',
            ],

            'checkbox_list' => [
                'bulkToggleable'         => 'Bulk Toggleable',
                'columns'                => 'Columns',
                'default'                => 'Default Value',
                'disabled'               => 'Disabled',
                'gridDirection'          => 'Grid Direction',
                'helperText'             => 'Helper Text',
                'hint'                   => 'Hint',
                'hintColor'              => 'Hint Color',
                'hintIcon'               => 'Hint Icon',
                'id'                     => 'Id',
                'maxItems'               => 'Max Items',
                'minItems'               => 'Min Items',
                'noSearchResultsMessage' => 'No Search Results Message',
                'searchable'             => 'Searchable',
            ],

            'datetime' => [
                'closeOnDateSelection' => 'Close on Date Selection',
                'default'              => 'Default Value',
                'disabled'             => 'Disabled',
                'disabledDates'        => 'Disabled Dates',
                'displayFormat'        => 'Display Format',
                'firstDayOfWeek'       => 'First Day of Week',
                'format'               => 'Format',
                'helperText'           => 'Helper Text',
                'hint'                 => 'Hint',
                'hintColor'            => 'Hint Color',
                'hintIcon'             => 'Hint Icon',
                'hoursStep'            => 'Hours Step',
                'id'                   => 'Id',
                'locale'               => 'Locale',
                'minutesStep'          => 'Minutes Step',
                'seconds'            => 'Seconds',
                'secondsStep'        => 'Seconds Step',
                'timezone'           => 'Timezone',
                'weekStartsOnMonday' => 'Week Starts on Monday',
                'weekStartsOnSunday' => 'Week Starts on Sunday',
            ],

            'editor' => [
                'default'     => 'Default Value',
                'disabled'    => 'Disabled',
                'helperText'  => 'Helper Text',
                'hint'        => 'Hint',
                'hintColor'   => 'Hint Color',
                'hintIcon'    => 'Hint Icon',
                'id'          => 'Id',
                'placeholder' => 'Placeholder',
                'readOnly'    => 'Read Only',
            ],

            'markdown' => [
                'default'     => 'Default Value',
                'disabled'    => 'Disabled',
                'helperText'  => 'Helper Text',
                'hint'        => 'Hint',
                'hintColor'   => 'Hint Color',
                'hintIcon'    => 'Hint Icon',
                'id'          => 'Id',
                'placeholder' => 'Placeholder',
                'readOnly'    => 'Read Only',
            ],

            'color' => [
                'default'    => 'Default Value',
                'disabled'   => 'Disabled',
                'helperText' => 'Helper Text',
                'hint'       => 'Hint',
                'hintColor'  => 'Hint Color',
                'hintIcon'   => 'Hint Icon',
                'hsl'        => 'HSL',
                'id'         => 'Id',
                'rgb'        => 'RGB',
                'rgba'       => 'RGBA',
            ],

            'file' => [
                'acceptedFileTypes'                => 'Accepted File Types',
                'appendFiles'                      => 'Append Files',
                'deletable'                        => 'Deletable',
                'directory'                        => 'Directory',
                'downloadable'                     => 'Downloadable',
                'fetchFileInformation'             => 'Fetch File Information',
                'fileAttachmentsDirectory'         => 'File Attachments Directory',
                'fileAttachmentsVisibility'        => 'File Attachments Visibility',
                'image'                            => 'Image',
                'imageCropAspectRatio'             => 'Image Crop Aspect Ratio',
                'imageEditor'                      => 'Image Editor',
                'imageEditorAspectRatios'          => 'Image Editor Aspect Ratios',
                'imageEditorEmptyFillColor'        => 'Image Editor Empty Fill Color',
                'imageEditorMode'                  => 'Image Editor Mode',
                'imagePreviewHeight'               => 'Image Preview Height',
                'imageResizeMode'                  => 'Image Resize Mode',
                'imageResizeTargetHeight'          => 'Image Resize Target Height',
                'imageResizeTargetWidth'           => 'Image Resize Target Width',
                'loadingIndicatorPosition'         => 'Loading Indicator Position',
                'moveFiles'                        => 'Move Files',
                'openable'                         => 'Openable',
                'orientImagesFromExif'             => 'Orient Images from EXIF',
                'panelAspectRatio'                 => 'Panel Aspect Ratio',
                'panelLayout'                      => 'Panel Layout',
                'previewable'                      => 'Previewable',
                'removeUploadedFileButtonPosition' => 'Remove Uploaded File Button Position',
                'reorderable'                      => 'Reorderable',
                'storeFiles'                       => 'Store Files',
                'uploadButtonPosition'             => 'Upload Button Position',
                'uploadingMessage'                 => 'Uploading Message',
                'uploadProgressIndicatorPosition'  => 'Upload Progress Indicator Position',
                'visibility'                       => 'Visibility',
            ]
        ]
    ],

    'table' => [
        'columns' => [
            'code' => 'Code',
            'name' => 'Name',
            'type' => 'Type',
            'resource' => 'Resource',
            'created-at' => 'Created At'
        ],

        'filters' => [
            'type' => [
                'label' => 'Type',
                'types' => [
                    'text' => 'Text Input',
                    'textarea' => 'Textarea',
                    'select' => 'Select',
                    'checkbox' => 'Checkbox',
                    'radio' => 'Radio',
                    'toggle' => 'Toggle',
                    'checkbox-list' => 'Checkbox List',
                    'datetime' => 'Date Time Picker',
                    'editor' => 'Rich Text Editor',
                    'markdown' => 'Markdown Editor',
                    'color' => 'Color Picker'
                ]
            ],
            'resource' => [
                'label' => 'Resource'
            ]
        ]
    ]
];
