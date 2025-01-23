<?php

return [
    'navigation' => [
        'title' => 'Packages',
        'group' => 'Inventory',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title' => 'General',

                'fields' => [
                    'name'             => 'Name',
                    'name-placeholder' => 'eg. PACK007',
                    'package-type'     => 'Package Type',
                    'pack-date'        => 'Pack Date',
                    'location'         => 'Location',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name'         => 'Name',
            'package-type' => 'Package Type',
            'location'     => 'Location',
            'company'      => 'Company',
            'created-at'   => 'Created At',
            'updated-at'   => 'Updated At',
        ],

        'groups' => [
            'package-type'   => 'Package Type',
            'location'       => 'Location',
            'created-at'     => 'Created At',
        ],

        'filters' => [
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Product deleted',
                    'body'  => 'The product has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Products deleted',
                    'body'  => 'The products has been deleted successfully.',
                ],
            ],
        ],
    ],
];
