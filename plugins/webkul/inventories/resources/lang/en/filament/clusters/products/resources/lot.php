<?php

return [
    'navigation' => [
        'title' => 'Lots / Serial Numbers',
        'group' => 'Inventory',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title' => 'General',

                'fields' => [
                    'name'             => 'Name',
                    'name-placeholder' => 'e.g. LOT/0001/20121',
                    'product'          => 'Product',
                    'product-hint-tooltip' => 'Product this lot/serial number contains. You cannot change it anymore if it has already been moved.',
                    'reference'        => 'Reference',
                    'reference-hint-tooltip' => 'Internal reference number in case it differs from the manufacturer\'s lot/serial number',
                    'description'      => 'Description',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name'     => 'Name',
            'product'  => 'Product',
            'on-hand-qty' => 'On Hand Quantity',
            'reference' => 'Internal Reference',
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
                    'title' => 'Lot deleted',
                    'body'  => 'The lot has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Lots deleted',
                    'body'  => 'The lots has been deleted successfully.',
                ],
            ],
        ],
    ],
];
