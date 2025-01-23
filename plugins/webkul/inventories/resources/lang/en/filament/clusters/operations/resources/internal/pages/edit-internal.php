<?php

return [
    'notification' => [
        'title' => 'Internal Transfer updated',
        'body'  => 'The internal transfer has been updated successfully.',
    ],

    'header-actions' => [
        'todo' => [
            'label' => 'Mark as Todo',

            'notification' => [
                'warning' => [
                    'title' => 'Internal transfer has no moves',
                    'body'  => 'The internal transfer has no moves to mark as todo.',
                ],

                'success' => [
                    'title' => 'Internal transfer marked as todo',
                    'body'  => 'The internal transfer has been marked as todo successfully.',
                ],
            ],
        ],

        'validate' => [
            'label' => 'Validate',

            'notification' => [
                'warning' => [
                    'title' => 'Insufficient stock',
                    'body'  => 'The internal transfer has insufficient stock to validate.',
                ],
            ],
        ],

        'return' => [
            'label' => 'Return',
        ],

        'delete' => [
            'notification' => [
                'title' => 'Delivery deleted',
                'body'  => 'The internal transfer has been deleted successfully.',
            ],
        ],
    ],
];
