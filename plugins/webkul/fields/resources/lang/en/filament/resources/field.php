<?php

return [
    'navigation' => [
        'title' => 'Custom Fields',
        'group' => 'Settings',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'fields' => [
                    'name' => 'Name',
                    'code' => 'code',
                    'code-helper-text'  => 'Code must start with a letter or underscore, and can only contain letters, numbers, and underscores.',
                ],
            ],

            'options' => [
                'title' => 'Options',
                
                'fields' => [
                    'add-option' => 'Add Option',
                ]
            ],

            'form-settings' => [
                'title' => 'Form Settings',

                'field-sets' => [
                    'validations' => [
                        'title' => 'Validations',

                        'fields' => [
                            'validation' => 'Validation',
                            'field' => 'Field',
                            'value' => 'Value',
                            'add-validation' => 'Add Validation',
                        ],
                    ],

                    'additional-settings' => [
                        'title' => 'Additional Settings',

                        'fields' => [
                            'setting' => 'Setting',
                            'value' => 'Value',
                            'color' => 'Color',
                            'add-setting' => 'Add Setting',

                            'color-options' => [
                                'danger'    => 'Danger',
                                'info'      => 'Info',
                                'primary'   => 'Primary',
                                'secondary' => 'Secondary',
                                'warning'   => 'Warning',
                                'success'   => 'Success',
                            ],

                            'grid-options' => [
                                'row' => 'Row',
                                'column' => 'Column',
                            ],

                            'input-modes' => [
                                'text'     => 'Text',
                                'email'    => 'Email',
                                'numeric'  => 'Numeric',
                                'integer'  => 'Integer',
                                'password' => 'Password',
                                'tel'      => 'Telephone',
                                'url'      => 'URL',
                                'color'    => 'Color',
                                'none'     => 'None',
                                'decimal'  => 'Decimal',
                                'search'   => 'Search',
                                'url'      => 'URL',
                            ]
                        ],
                    ]
                ],
            ],

            'table-settings' => [
                'title' => 'Table Settings',

                'fields' => [
                    'use-in-table' => 'Use in Table',
                    'setting' => 'Setting',
                    'value' => 'Value',
                    'color' => 'Color',
                    'alignment' => 'Alignment',

                    'color-options' => [
                        'danger'    => 'Danger',
                        'info'      => 'Info',
                        'primary'   => 'Primary',
                        'secondary' => 'Secondary',
                        'warning'   => 'Warning',
                        'success'   => 'Success',
                    ],

                    'alignment-options' => [
                        'start'   => 'Start',
                        'left'    => 'Left',
                        'center'  => 'Center',
                        'end'     => 'End',
                        'right'   => 'Right',
                        'justify' => 'Justify',
                        'between' => 'Between',
                    ],
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [

        ],

        'groups' => [
        ],

        'filters' => [
        ],

        'actions' => [
        ],
    ],
];