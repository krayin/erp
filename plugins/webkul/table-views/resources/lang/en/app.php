<?php

return [
    'navigation' => [
        'label' => 'Custom Fields',
        'group' => 'Settings',
    ],

    'model-label' => 'Fields',

    'actions' => [
        'create-view' => [
            'form' => [
                'name'                  => 'Name',
                'color'                 => 'Color',
                'icon'                  => 'Icon',
                'add-to-favorites'      => 'Add To Favorites',
                'add-to-favorites-help' => 'Add this filter to your favorites',
                'make-public'           => 'Make Public',
                'make-public-help'      => 'Make this filter available to all users',
                'options'               => [
                    'danger'  => 'Danger',
                    'gray'    => 'Gray',
                    'info'    => 'Information',
                    'success' => 'Success',
                    'warning' => 'Warning',
                ],

                'notification' => [
                    'created' => 'View created successfully',
                ],

                'modal' => [
                    'title' => 'Save View',
                ],
            ],
        ],

        'edit-view' => [
            'form' => [
                'name'                  => 'Name',
                'color'                 => 'Color',
                'icon'                  => 'Icon',
                'add-to-favorites'      => 'Add To Favorites',
                'add-to-favorites-help' => 'Add this filter to your favorites',
                'make-public'           => 'Make Public',
                'make-public-help'      => 'Make this filter available to all users',
                'options'               => [
                    'danger'  => 'Danger',
                    'gray'    => 'Gray',
                    'info'    => 'Information',
                    'success' => 'Success',
                    'warning' => 'Warning',
                ],

                'notification' => [
                    'created' => 'View created successfully',
                ],

                'modal' => [
                    'title' => 'Edit View',
                ],
            ],
        ],
    ],

    'views' => [
        'default' => 'Default',
        'title' => 'Views',
        'apply-view' => 'Apply View',
        'add-to-favorites' => 'Add to Favorites',
        'remove-from-favorites' => 'Remove from Favorites',
        'delete-view' => 'Delete View',
        'replace-view' => 'Replace View',
        'reset' => 'Reset',
        'favorites-views' => 'Favorites Views',
        'saved-views' => 'Saved Views',
        'preset-views' => 'Preset Views',
    ]
];
