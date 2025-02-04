<?php

return [
    'navigation' => [
        'title' => 'Attributes',
        'group' => 'Products',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'name' => 'Name',
                    'type' => 'Type',
                ],
            ],

            'options' => [
                'title'  => 'Options',

                'fields' => [
                    'name'        => 'Name',
                    'color'       => 'Color',
                    'extra-price' => 'Extra Price',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name'        => 'Name',
            'type'        => 'Type',
            'deleted-at'  => 'Deleted At',
            'created-at'  => 'Created At',
            'updated-at'  => 'Updated At',
        ],

        'groups' => [
            'type'       => 'Type',
            'created-at' => 'Created At',
            'updated-at' => 'Updated At',
        ],

        'filters' => [
            'type' => 'Type',
        ],

        'actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Attribute restored',
                    'body'  => 'The Attribute has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Attribute deleted',
                    'body'  => 'The Attribute has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Attribute force deleted',
                    'body'  => 'The Attribute has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Attributes restored',
                    'body'  => 'The Attribute has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Attributes deleted',
                    'body'  => 'The Attribute has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Attributes force deleted',
                    'body'  => 'The Attribute has been force deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'general' => [
                'title' => 'General Information',

                'entries' => [
                    'name' => 'Name',
                    'type' => 'Type',
                ],
            ],

            'record-information' => [
                'title' => 'Record Information',

                'entries' => [
                    'creator' => 'Created By',
                    'created_at' => 'Created At',
                    'updated_at' => 'Last Updated At',
                ],
            ],
        ],
    ],
];
