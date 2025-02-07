<?php

return [
    'title' => 'Category',

    'navigation' => [
        'title' => 'Categories',
        'group' => 'Products',
    ],

    'global-search' => [
        'name' => 'Name',
    ],

    'form' => [
        'sections' => [
            'fields' => [
                'name' => 'Category Name',
                'parent-category' => 'Parent Category',
            ],
        ]
    ],

    'table' => [
        'columns' => [
            'id'              => 'ID',
            'complete-name'   => 'Product Category',
            'created-by'      => 'Created By',
            'created-at'      => 'Created At',
            'updated-at'      => 'Updated At',
        ],

        'filters' => [
            'name'            => 'Name',
            'complete-name'   => 'Complete Name',
            'parent-category' => 'Parent Category',
            'created-by'      => 'Created By',
            'created-by'      => 'Created By',
            'updated-at'      => 'Updated At',
            'created-at'      => 'Created At',
        ],

        'groups' => [
            'name'                 => 'Name',
            'complete-name'        => 'Complete Name',
            'parent-complete-name' => 'Parent Category',
            'created-by'           => 'Created By',
            'created-at'           => 'Created At',
            'updated-at'           => 'Updated At',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Category deleted',
                    'body'  => 'The category has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Category deleted',
                    'body'  => 'The Category has been deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'entries' => [
                'name'            => 'Category Name',
                'parent-category' => 'Parent Category',
            ],
        ]
    ],
];
