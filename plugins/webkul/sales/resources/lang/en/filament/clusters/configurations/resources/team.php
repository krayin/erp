<?php

return [
    'title' => 'Sales Teams',

    'navigation' => [
        'title' => 'Sales Teams',
    ],

    'global-search' => [
        'company-name'    => 'Company Name',
        'user-name'       => 'User Name',
        'name'            => 'Name',
        'invoiced-target' => 'Invoiced Target',
    ],

    'form' => [
        'sections' => [
            'fields' => [
                'name' => 'Sales Team',
                'status' => 'Status',
                'fieldset' => [
                    'team-details' => [
                        'title' => 'Team Details',
                        'fields' => [
                            'team-leader'            => 'Team Leader',
                            'company'                => 'Company',
                            'invoiced-target'        => 'Invoiced Target',
                            'invoiced-target-suffix' => '/ Month',
                            'color'                  => 'Color',
                            'members'                => 'Members',
                        ]
                    ]
                ]
            ],
        ]
    ],

    'table' => [
        'columns' => [
            'id'              => 'ID',
            'company'         => 'Company',
            'team-leader'     => 'Team Leader',
            'name'            => 'Name',
            'status'          => 'Status',
            'invoiced-target' => 'Invoiced Target',
            'color'           => 'Color',
            'created-by'      => 'Created By',
            'created-at'      => 'Created At',
            'updated-at'      => 'Updated At',
        ],

        'filters' => [
            'name'        => 'Name',
            'team-leader' => 'Team Leader',
            'company'     => 'Company',
            'created-by'  => 'Created By',
            'updated-at'  => 'Updated At',
            'created-at'  => 'Created At',
        ],

        'groups' => [
            'name'        => 'Name',
            'company'     => ' Company',
            'team-leader' => 'Team Leader',
            'created-at'  => 'Created At',
            'updated-at'  => 'Updated At',
        ],

        'actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Sales Team restored',
                    'body'  => 'The sales Team has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Sales Team deleted',
                    'body'  => 'The sales Team has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Sales Team force deleted',
                    'body'  => 'The sales Team has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Sales Teams restored',
                    'body'  => 'The sales Teams has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Sales Teams deleted',
                    'body'  => 'The sales Teams has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Sales Teams force deleted',
                    'body'  => 'The sales Teams has been force deleted successfully.',
                ],
            ],
        ],

        'empty-state-action' => [
            'create' => [
                'notification' => [
                    'title' => 'Sales Teams created',
                    'body'  => 'The sales Teams has been created successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'entries' => [
                'name' => 'Sales Team',
                'status' => 'Status',
                'fieldset' => [
                    'team-details' => [
                        'title' => 'Team Details',
                        'entries' => [
                            'team-leader'            => 'Team Leader',
                            'company'                => 'Company',
                            'invoiced-target'        => 'Invoiced Target',
                            'invoiced-target-suffix' => '/ Month',
                            'color'                  => 'Color',
                            'members'                => 'Members',
                        ]
                    ]
                ]
            ],
        ]
    ],
];
