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
        'name'        => 'Name',
    ],

    'table' => [
        'columns' => [
            'id'             => 'ID',
            'name'           => 'Name',
            'created-by'     => 'Created By',
            'created-at'     => 'Created At',
            'updated-at'     => 'Updated At',
        ],

        'filters' => [
            'name'              => 'Name',
            'employee'          => 'Employee',
            'created-by'        => 'Created By',
            'updated-at'        => 'Updated At',
            'created-at'        => 'Created At',
        ],

        'groups' => [
            'name'           => 'Schedule Name',
            'status'         => 'Status',
            'timezone'       => 'Timezone',
            'flexible-hours' => 'Flexible Hours',
            'daily-hours'    => 'Daily Hours',
            'created-by'     => 'Created By',
            'created-at'     => 'Created At',
            'updated-at'     => 'Updated At',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Departure reason Plan restored',
                    'body'  => 'The departure reason plan has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Departure reason Plan deleted',
                    'body'  => 'The departure reason plan has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Departure reason deleted',
                    'body'  => 'The departure reason has been deleted successfully.',
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
