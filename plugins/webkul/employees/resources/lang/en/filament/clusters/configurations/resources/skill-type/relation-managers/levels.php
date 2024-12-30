<?php

return [
    'form' => [
        'name'          => 'Name',
        'level'         => 'Level',
        'default-level' => 'Default Level',
    ],

    'table' => [
        'columns' => [
            'name'          => 'Name',
            'level'         => 'Level',
            'default-level' => 'Default Level',
            'created-at'    => 'Created At',
            'updated-at'    => 'Updated At',
        ],

        'groups' => [
            'created-at' => 'Created At',
        ],

        'filters' => [
            'deleted-records' => 'Deleted Records',
        ],

        'actions' => [
            'create' => [
                'notification' => [
                    'title' => 'Skill level',
                    'body'  => 'The skill level has been created successfully.',
                ],
            ],

            'edit' => [
                'notification' => [
                    'title' => 'Skill level',
                    'body'  => 'The skill level has been updated successfully.',
                ],
            ],

            'restore' => [
                'notification' => [
                    'title' => 'Skill restored',
                    'body'  => 'The skill level has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Skill deleted',
                    'body'  => 'The skill level has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Skills deleted',
                    'body'  => 'The skills has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Skills force deleted',
                    'body'  => 'The skills has been force deleted successfully.',
                ],
            ],

            'restore' => [
                'notification' => [
                    'title' => 'Skills force restored',
                    'body'  => 'The skills has been force restored successfully.',
                ],
            ]
        ],
    ],

    'infolist' => [
        'entries' => [
            'name'          => 'Name',
            'level'         => 'Level',
            'default-level' => 'Default Level',
        ]
    ],
];
