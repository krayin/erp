<?php

return [
    'navigation' => [
        'title' => 'Packagings',
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
                    'title' => 'Packaging deleted',
                    'body'  => 'The packaging has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Packaging deleted',
                    'body'  => 'The packaging has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
