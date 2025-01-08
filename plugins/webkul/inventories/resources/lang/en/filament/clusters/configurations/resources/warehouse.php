<?php

return [
    'navigation' => [
        'title' => 'Warehouses',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',
                'fields' => [
                    'name'               => 'Name',
                    'name-placeholder'   => 'eg. Central Warehouse',
                    'code'               => 'Short Name',
                    'code-placeholder'   => 'eg. CW',
                    'code-hint-tooltip'  => 'The short name is used to identify the warehouse.',
                    'company'            => 'Company',
                    'address'            => 'Address',
                ],
            ],

            'settings' => [
                'title'  => 'Settings',

                'fields' => [
                    'shipment-management'              => 'Shipment Management',
                    'incoming-shipments'               => 'Incoming Shipments',
                    'incoming-shipments-hint-tooltip'  => 'Default incoming route to follow',
                    'outgoing-shipments'               => 'Outgoing Shipments',
                    'outgoing-shipments-hint-tooltip'  => 'Default outgoing route to follow',
                    'resupply-management'              => 'Resupply Management',
                    'resupply-management-hint-tooltip' => 'Routes will be created automatically to resupply this warehouse from the warehouses ticked',
                    'resupply-from'                    => 'Resupply From',
                ],
            ],

            'additional' => [
                'title'  => 'Additional Information',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name'       => 'Name',
            'code'       => 'Short Name',
            'company'    => 'Company',
            'address'    => 'Address',
            'deleted-at' => 'deleted At',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'groups' => [
            'address'       => 'Address',
            'company'       => 'Company',
            'created-at'    => 'Created At',
            'updated-at'    => 'Updated At',
        ],

        'actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Warehouse restored',
                    'body'  => 'The warehouse has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Warehouse deleted',
                    'body'  => 'The warehouse has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Warehouse force deleted',
                    'body'  => 'The warehouse has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Warehouses restored',
                    'body'  => 'The warehouses has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Warehouses deleted',
                    'body'  => 'The warehouses has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Warehouses force deleted',
                    'body'  => 'The warehouses has been force deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
