<?php

return [
    'navigation' => [
        'title' => 'Categories',
        'group' => 'Products',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'name'             => 'Name',
                    'name-placeholder' => 'eg. Two Step Reception',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
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
                    'title' => 'Category deleted',
                    'body'  => 'The Category has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Categories deleted',
                    'body'  => 'The categories has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
