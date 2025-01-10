<?php

return [
    'navigation' => [
        'title' => 'Package Types',
        'group' => 'Delivery',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'name' => 'Name',
                    'barcode' => 'Barcode',
                    'weight' => 'Weight',
                    'max-weight' => 'Max Weight',

                    'fieldsets' => [
                        'size' => [
                            'title' => 'Size',

                            'fields' => [
                                'length' => 'Length',
                                'width'  => 'Width',
                                'height' => 'Height',
                            ],
                        ]
                    ],
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name' => 'Name',
            'barcode' => 'Barcode',
            'weight' => 'Weight',
            'max-weight' => 'Max Weight',
            'width' => 'Width',
            'height' => 'Height',
            'length' => 'Length',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'groups' => [
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Package Type deleted',
                    'body'  => 'The package type has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Package Type deleted',
                    'body'  => 'The package type has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
