<?php

return [
    'breadcrumb' => 'Manage Users',
    'title' => 'Manage Users',

    'navigation' => [
        'label' => 'Manage Users',
    ],

    'form' => [
        'enable-user-invitation' => [
            'label'       => 'Enable User Invitation',
            'helper-text' => 'Allow users to invite other users to the application.',
        ],

        'enable-reset-password' => [
            'label' => 'Enable Reset Password',
            'helper-text' => 'Allow users to reset their password.',
        ],

        'default-role' => [
            'label' => 'Default Role',
            'helper-text' => 'The default role assigned to new users.',
        ],
    ],
];
