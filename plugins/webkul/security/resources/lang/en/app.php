<?php

return [
    'filament' => [
        'clusters' => [
            'pages' => [
                'manage-users' => [
                    'enable-user-invitation'             => 'Enable User Invitation',
                    'enable-user-invitation-helper-text' => 'Allow admins to invite users by email with assigned roles and permissions.',
                    'enable-reset-password'              => 'Enable Reset Password',
                    'enable-reset-password-helper-text'  => 'Allow users to reset their passwords from login page.',
                    'default-role'                       => 'Default Role',
                    'default-role-helper-text'           => 'Role assigned to users upon registration via invitation.',
                ],
            ],

            'settings' => [
                'name'  => 'Settings',
                'group' => 'Settings',
            ],
        ],

        'resources' => [
            'user' => [
                'title' => 'Users',

                'pages' => [
                    'create' => [
                        'created-notification-title' => 'User registered',
                    ],

                    'edit' => [
                        'header-actions' => [
                            'action' => [
                                'title'        => 'Change Password',
                                'notification' => [
                                    'title' => 'Saved successfully',
                                ],
                            ],
                            'form' => [
                                'new-password'         => 'New Password',
                                'confirm-new-password' => 'Confirm New Password',
                            ],
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                            'all'      => 'All Users',
                            'archived' => 'Archived',
                        ],

                        'header-actions' => [
                            'invite-user' => [
                                'title' => 'Invite User',
                                'modal' => [
                                    'title'               => 'Invite User',
                                    'submit-action-label' => 'Invite User',
                                ],
                                'notification' => [
                                    'title' => 'User invited successfully!',
                                ],
                            ],

                            'form' => [
                                'email' => 'Email',
                            ],
                        ],
                    ],
                ],

                'navigation' => [
                    'title' => 'Users',
                    'group' => 'Settings',
                ],

                'form' => [
                    'sections' => [
                        'general' => [
                            'title'  => 'General',
                            'fields' => [
                                'name'                  => 'Name',
                                'email'                 => 'Email',
                                'password'              => 'Password',
                                'password-confirmation' => 'Confirm New Password',
                            ],
                        ],

                        'permissions' => [
                            'title'  => 'Permissions',
                            'fields' => [
                                'roles'               => 'Roles',
                                'resource-permission' => 'Resource Permission',
                                'teams'               => 'Teams',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'name'                => 'Name',
                        'email'               => 'Email',
                        'teams'               => 'Teams',
                        'role'                => 'Role',
                        'resource-permission' => 'Resource Permission',
                        'created-at'          => 'Created At',
                        'updated-at'          => 'Updated At',
                    ],

                    'filters' => [
                        'resource-permission' => 'Resource Permission',
                        'teams'               => 'Teams',
                        'roles'               => 'Roles',
                    ],
                ],
            ],

            'team' => [
                'title' => 'Teams',

                'navigation' => [
                    'title' => 'Teams',
                    'group' => 'Settings',
                ],

                'form' => [
                    'name' => 'Name',
                ],

                'table' => [
                    'name' => 'Name',
                ],
            ],

            'role' => [
                'navigation' => [
                    'title' => 'Roles',
                    'group' => 'Settings',
                ],
            ],
        ],
    ],

    'livewire' => [
        'header' => [
            'sub-heading' => [
                'accept-invitation' => 'Create your user to accept an invitation',
            ],
        ],
    ],

    'mail' => [
        'user-invitation' => [
            'subject' => 'Invitation to join :app',
            'accept'  => [
                'button' => 'Accept Invitation',
            ],
        ],
    ],
];
