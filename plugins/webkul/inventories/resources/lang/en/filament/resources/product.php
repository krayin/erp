<?php

return [
    'navigation' => [
        'title' => 'Products',
        'group' => 'Inventory',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title' => 'General',

                'fields' => [
                    'name'             => 'Name',
                    'name-placeholder' => 'Project Name...',
                    'description'      => 'Description',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
        ],

        'groups' => [
            'stage'           => 'Stage',
            'project-manager' => 'Project Manager',
            'customer'        => 'Customer',
            'created-at'      => 'Created At',
        ],

        'filters' => [
        ],

        'actions' => [
            'tasks'      => ':count Tasks',
            'milestones' => ':completed milestones completed out of :all',

            'restore' => [
                'notification' => [
                    'title' => 'Project restored',
                    'body'  => 'The project has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Project deleted',
                    'body'  => 'The project has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Project force deleted',
                    'body'  => 'The project has been force deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        
    ],
];
