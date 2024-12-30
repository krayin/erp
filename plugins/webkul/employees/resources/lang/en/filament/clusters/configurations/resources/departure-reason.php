<?php

return [
    'navigation' => [
        'title' => 'Departure Reasons',
        'group' => 'Employee',
    ],

    'modal-label' => 'Departure Reasons',

    'groups' => [
        'status'     => 'Status',
        'created-by' => 'Created By',
        'created-at' => 'Created At',
        'updated-at' => 'Updated At',
    ],

    'global-search' => [
        'name'        => 'Name',
        'reason-code' => 'Reason Code',
    ],

    'form' => [
        'fields' => [
            'name' => 'Name',
        ]
    ],

    'table' => [
        'columns' => [
            'id'         => 'ID',
            'name'       => 'Name',
            'created-by' => 'Created By',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'filters' => [
            'name'       => 'Name',
            'employee'   => 'Employee',
            'created-by' => 'Created By',
            'updated-at' => 'Updated At',
            'created-at' => 'Created At',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Departure reason updated',
                    'body'  => 'The departure reason has been updated successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Departure reason deleted',
                    'body'  => 'The departure reason has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Departure reasons deleted',
                    'body'  => 'The departure reasons has been deleted successfully.',
                ],
            ],
        ],

        'empty-state-action' => [
            'create' => [
                'notification' => [
                    'title' => 'Departure reason created',
                    'body'  => 'The departure reason has been created successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name' => 'Name',
    ],
];
