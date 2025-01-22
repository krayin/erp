<?php

return [
    'navigation' => [
        'title' => 'Deliveries',
        'group' => 'Transfers',
    ],

    'table' => [
        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Delivery deleted',
                    'body'  => 'The delivery ras been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Deliveries deleted',
                    'body'  => 'The deliveries has been deleted successfully.',
                ],
            ],
        ],
    ],
];
