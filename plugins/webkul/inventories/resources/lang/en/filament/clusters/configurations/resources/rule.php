<?php

return [
    'navigation' => [
        'title' => 'Rules',
        'group' => 'Warehouse Management',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'name'             => 'Name',
                ],
            ],

            'applicable-on' => [
                'title'       => 'Applicable On',
                'description' => 'Select the places where this route can be selected.',

                'fields' => [
                    'products'                        => 'Products',
                    'products-hint-tooltip'           => 'When checked, the route will be selectable on the Product.',
                    'product-categories'              => 'Product Categories',
                    'product-categories-hint-tooltip' => 'When checked, the route will be selectable on the Product Category.',
                    'warehouses'                      => 'Warehouses',
                    'warehouses-hint-tooltip'         => 'When a warehouse is selected for this route, this route should be seen as the default route when products pass through this warehouse.',
                    'packaging'                       => 'Packaging',
                    'packaging-hint-tooltip'          => 'When checked, the route will be selectable on the Packaging.',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'route'      => 'Route',
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
                    'title' => 'Rule restored',
                    'body'  => 'The rule has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Rule deleted',
                    'body'  => 'The rule has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Rule force deleted',
                    'body'  => 'The rule has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Rules restored',
                    'body'  => 'The rules has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Rules deleted',
                    'body'  => 'The rules has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Rules force deleted',
                    'body'  => 'The rules has been force deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
