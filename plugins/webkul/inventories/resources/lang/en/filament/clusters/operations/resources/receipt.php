<?php

return [
    'navigation' => [
        'title' => 'Receipts',
        'group' => 'Transfers',
    ],

    'table' => [
        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Receipt deleted',
                    'body'  => 'The Receipt ras been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Receipts deleted',
                    'body'  => 'The receipts has been deleted successfully.',
                ],
            ],
        ],
    ],
];
