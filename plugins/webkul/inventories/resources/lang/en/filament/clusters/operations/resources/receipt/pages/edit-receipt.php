<?php

return [
    'notification' => [
        'title' => 'Receipt updated',
        'body'  => 'The receipt has been updated successfully.',
    ],

    'header-actions' => [
        'todo' => [
            'label' => 'Mark as Todo',

            'notification' => [
                'warning' => [
                    'title' => 'Receipt has no moves',
                    'body'  => 'The receipt has no moves to mark as todo.',
                ],

                'success' => [
                    'title' => 'Receipt marked as todo',
                    'body'  => 'The receipt has been marked as todo successfully.',
                ],
            ],
        ],

        'validate' => [
            'label' => 'Validate',


            'notification' => [
                'warning' => [
                    'title' => 'Insufficient stock',
                    'body'  => 'The receipt has insufficient stock to validate.',
                ],
            ],
        ],

        'return' => [
            'label' => 'Return',
        ],

        'delete' => [
            'notification' => [
                'title' => 'Receipt deleted',
                'body'  => 'The receipt has been deleted successfully.',
            ],
        ],
    ],
];
