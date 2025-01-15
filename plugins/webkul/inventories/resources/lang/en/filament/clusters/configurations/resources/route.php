<?php

return [
    'navigation' => [
        'title' => 'Routes',
        'group' => 'Warehouse Management',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'route'             => 'Route',
                    'route-placeholder' => 'eg. Two Step Reception',
                    'company'           => 'Company',
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
            'company'    => 'Company',
            'deleted-at' => 'Deleted At',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'groups' => [
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Route updated',
                    'body'  => 'The route has been updated successfully.',
                ],
            ],

            'restore' => [
                'notification' => [
                    'title' => 'Route restored',
                    'body'  => 'The route has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Route deleted',
                    'body'  => 'The route has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Route force deleted',
                    'body'  => 'The route has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Routes restored',
                    'body'  => 'The routes has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Routes deleted',
                    'body'  => 'The routes has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Routes force deleted',
                    'body'  => 'The routes has been force deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
