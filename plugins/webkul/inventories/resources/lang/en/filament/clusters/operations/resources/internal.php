<?php

return [
    'navigation' => [
        'title' => 'Internal Transfers',
        'group' => 'Transfers',
    ],

    'table' => [
        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Internal Transfer deleted',
                    'body'  => 'The internal transfer ras been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Internal Transfers deleted',
                    'body'  => 'The internal transfers has been deleted successfully.',
                ],
            ],
        ],
    ],
];
