<?php

return [
    'navigation' => [
        'title' => 'Operation Types',
        'group' => 'Warehouse Management',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'fields' => [
                    'operator-type' => 'Operator Type',
                    'operator-type-placeholder' => 'eg. Receptions',
                ],
            ],

            'applicable-on' => [
                'title'  => 'Applicable On',
                'description' => 'Select the places where this route can be selected.',

                'fields' => [
                ],
            ],
        ],

        'tabs' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'operator-type' => 'Operator Type',
                    'sequence-prefix' => 'Sequence Prefix',
                    'generate-shipping-labels' => 'Generate Shipping Labels',
                    'warehouse' => 'Warehouse',
                    'show-reception-report' => 'Show Reception Report at Validation',
                    'show-reception-report-hint-tooltip' => 'If checked, System will automatically show the reception report (if there are moves to allocate to) when validating.',
                    'return-type' => 'Return Type',
                    'create-backorder' => 'Create Backorder',
                    'move-type' => 'Move Type',
                    'move-type-hint-tooltip' => 'Unless previously specified by the source document, this will be the default picking policy for this operation type.'
                ],

                'fieldsets' => [
                    'lots' => [
                        'title'  => 'Lots/Serial Numbers',

                        'fields' => [
                            'create-new' => 'Create New',
                            'create-new-hint-tooltip' => 'If this is checked only, it will suppose you want to create new Lots/Serial Numbers, so you can provide them in a text field.',
                            'use-existing' => 'Use Existing',
                            'use-existing-hint-tooltip' => 'If this is checked, you will be able to choose the Lots/Serial Numbers. You can also decide to not put lots in this operation type.  This means it will create stock with no lot or not put a restriction on the lot taken.',
                        ],
                    ],

                    'locations' => [
                        'title'  => 'Locations',

                        'fields' => [
                            'source-location' => 'Source Location',
                            'source-location-hint-tooltip' => 'This is the default source location when this operation is manually created. However, it is possible to change it afterwards or that the routes use another one by default.',
                            'destination-location' => 'Destination Location',
                            'destination-location-hint-tooltip' => 'This is the default destination location when this operation is manually created. However, it is possible to change it afterwards or that the routes use another one by default.',
                        ],
                    ],

                    'packages' => [
                        'title'  => 'Packages',

                        'fields' => [
                            'show-entire-package' => 'Move Entire Package',
                            'show-entire-package-hint-tooltip' => 'If checked, you will be able to select entire packages to move',
                        ],
                    ],
                ],
            ],

            'hardware' => [
                'title'  => 'Hardware',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'deleted-at' => 'Deleted At',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'groups' => [
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Operation Type restored',
                    'body'  => 'The operation type has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Operation Type deleted',
                    'body'  => 'The operation type has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Operation Type force deleted',
                    'body'  => 'The operation type has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Operation Types restored',
                    'body'  => 'The operation types has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Operation Types deleted',
                    'body'  => 'The operation types has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Operation Types force deleted',
                    'body'  => 'The operation types has been force deleted successfully.',
                ],
            ],
        ],

        'empty-actions' => [
            'create' => [
                'label' => 'Create Operation Type',
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
