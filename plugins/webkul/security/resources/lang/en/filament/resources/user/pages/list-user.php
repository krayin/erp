<?php

return [
    'tabs' => [
        'all'      => 'All Users',
        'archived' => 'Archived Users',
    ],

    'header-actions' => [
        'invite' => [
            'title' => 'Invite User',
            'modal' => [
                'submit-action-label' => 'Invite User',
            ],
            'form' => [
                'email' => 'Email',
            ],
            'notification' => [
                'success' => [
                    'title' => 'User invited',
                    'body'  => 'User has been invited successfully',
                ],
                'error' => [
                    'title' => 'User Invitation Failed',
                    'body'  => 'The system encountered an unexpected error while trying to send the user invitation.',
                ],
            ],
        ],

        'create' => [
            'label' => 'New User',
        ],
    ],
];
