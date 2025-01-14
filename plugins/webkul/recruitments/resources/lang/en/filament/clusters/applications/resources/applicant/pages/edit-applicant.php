<?php

return [
    'notification' => [
        'title' => 'Applicant updated',
        'body'  => 'The applicant has been updated successfully.',
    ],

    'header-actions' => [
        'delete' => [
            'notification' => [
                'title' => 'Applicant deleted',
                'body'  => 'The applicant has been deleted successfully.',
            ],
        ],
        'force-delete' => [
            'notification' => [
                'title' => 'Applicant deleted',
                'body'  => 'The applicant has been force deleted successfully.',
            ],
        ],

        'refuse' => [
            'title' => 'Refuse Reason',
            'notification' => [
                'title' => 'Applicant refused',
                'body'  => 'The applicant has been refused successfully.',
            ],
        ],

        'reopen' => [
            'title' => 'Reopen Applicant',
            'notification' => [
                'title' => 'Applicant reopened',
                'body'  => 'The applicant has been reopened successfully.',
            ],
        ]
    ],
];
