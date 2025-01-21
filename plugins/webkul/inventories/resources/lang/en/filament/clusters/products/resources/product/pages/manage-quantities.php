<?php

return [
    'title' => 'Quantities',

    'form' => [
        'fields' => [
            'location'         => 'Location',
            'package'          => 'Package',
            'on-hand-qty'      => 'On Hand Quantity',
            'storage-category' => 'Storage Category',
        ],
    ],

    'table' => [
        'columns' => [
            'location'         => 'Location',
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
                        'title' => 'Location already exists',
                        'body'  => 'The location already has a quantity. Please update the quantity instead.',
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
