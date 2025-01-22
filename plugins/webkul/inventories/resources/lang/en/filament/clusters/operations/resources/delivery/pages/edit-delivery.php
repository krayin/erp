<?php

return [
    'notification' => [
        'title' => 'Delivery updated',
        'body'  => 'The delivery has been updated successfully.',
    ],

    'header-actions' => [
        'todo' => [
            'label' => 'Mark as Todo',

            'notification' => [
                'warning' => [
                    'title' => 'Delivery has no moves',
                    'body'  => 'The delivery has no moves to mark as todo.',
                ],

                'success' => [
                    'title' => 'Delivery marked as todo',
                    'body'  => 'The delivery has been marked as todo successfully.',
                ],
            ],
        ],

        'validate' => [
            'label' => 'Validate',

            'notification' => [
                'warning' => [
                    'title' => 'Insufficient stock',
                    'body'  => 'The delivery has insufficient stock to validate.',
                ],
            ],
        ],

        'return' => [
            'label' => 'Return',
        ],

        'delete' => [
            'notification' => [
                'title' => 'Delivery deleted',
                'body'  => 'The delivery has been deleted successfully.',
            ],
        ],
    ],
];
