<?php

return [
    'title' => 'Quantities',

    'form' => [
        'fields' => [
            'location'         => 'Location',
            'package'          => 'Package',
            'lot'              => 'Lot / Serial Numbers',
            'on-hand-qty'      => 'On Hand Quantity',
            'storage-category' => 'Storage Category',
        ],
    ],

    'table' => [
        'columns' => [
            'location'         => 'Location',
            'lot'              => 'Lot / Serial Numbers',
            'storage-category' => 'Storage Category',
            'quantity'         => 'Quantity',
            'package'          => 'Package',
            'on-hand'          => 'On Hand Quantity',
        ],

        'header-actions' => [
            'create' => [
                'label' => 'Add Quantity',

                'notification' => [
                    'title' => 'Quantity added',
                    'body'  => 'The quantity has been added successfully.',
                ],

                'before' => [
                    'notification' => [
                        'title' => 'Quantity already exists',
                        'body'  => 'Already has a quantity for the same configuration. Please update the quantity instead.',
                    ],
                ],
            ],
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Quantity deleted',
                    'body'  => 'The quantity has been deleted successfully.',
                ],
            ],
        ],
    ],
];
