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
                ],
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
