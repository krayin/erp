<?php

return [
    'navigation' => [
        'title' => 'Storage Categories',
        'group' => 'Warehouse Management',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'name'               => 'Name',
                    'allow-new-products' => 'Allow New Products',
                    'max-weight'         => 'Max Weight',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name'               => 'Name',
            'allow-new-products' => 'Allow New Products',
            'max-weight'         => 'Max Weight',
            'deleted-at'         => 'Deleted At',
            'created-at'         => 'Created At',
            'updated-at'         => 'Updated At',
        ],

        'groups' => [
            'allow-new-products' => 'Allow New Products',
            'created-at'         => 'Created At',
            'updated-at'         => 'Updated At',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Storage Category deleted',
                    'body'  => 'The storage category has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Storage Categories deleted',
                    'body'  => 'The storage categories has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
