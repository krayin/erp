<?php

return [
    'filament' => [
        'clusters' => [
            'pages' => [
                'manage-users' => [
                    'title'                              => 'Gérer les utilisateurs',
                    'enable-user-invitation'             => 'Activer l\'invitation d\'utilisateur',
                    'enable-user-invitation-helper-text' => 'Permettre aux administrateurs d\'inviter des utilisateurs par e-mail avec des rôles et des autorisations attribués.',
                    'enable-reset-password'              => 'Activer la réinitialisation du mot de passe',
                    'enable-reset-password-helper-text'  => 'Permettre aux utilisateurs de réinitialiser leurs mots de passe depuis la page de connexion.',
                    'default-role'                       => 'Rôle par défaut',
                    'default-role-helper-text'           => 'Rôle attribué aux utilisateurs lors de l\'inscription via une invitation.',
                ],
            ],

            'settings' => [
                'name'  => 'Paramètres',
                'group' => 'Paramètres',
            ],
        ],

        'resources' => [
            'user' => [
                'title' => 'Utilisateurs',

                'pages' => [
                    'create' => [
                        'created-notification-title' => 'Utilisateur enregistré',
                    ],

                    'edit' => [
                        'header-actions' => [
                            'action' => [
                                'title' => 'Enregistré avec succès',
                            ],
                            'form' => [
                                'new-password'         => 'Nouveau mot de passe',
                                'confirm-new-password' => 'Confirmer le nouveau mot de passe',
                            ],
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                            'all'      => 'Tous les utilisateurs',
                            'archived' => 'Archivés',
                        ],

                        'header-actions' => [
                            'invite-user' => [
                                'title' => 'Inviter un utilisateur',
                                'modal' => [
                                    'title'               => 'Inviter un utilisateur',
                                    'submit-action-label' => 'Inviter un utilisateur',
                                ],
                                'notification' => [
                                    'title' => 'Utilisateur invité avec succès !',
                                ],
                            ],

                            'form' => [
                                'email' => 'E-mail',
                            ],
                        ],
                    ],
                ],

                'navigation' => [
                    'title' => 'Utilisateurs',
                    'group' => 'Paramètres',
                ],

                'form' => [
                    'sections' => [
                        'general' => [
                            'title'  => 'Général',
                            'fields' => [
                                'name'                  => 'Nom',
                                'email'                 => 'E-mail',
                                'password'              => 'Mot de passe',
                                'password-confirmation' => 'Confirmer le nouveau mot de passe',
                            ],
                        ],

                        'permissions' => [
                            'title'  => 'Autorisations',
                            'fields' => [
                                'roles'               => 'Rôles',
                                'resource-permission' => 'Autorisation de ressource',
                                'teams'               => 'Équipes',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'name'                => 'Nom',
                        'email'               => 'E-mail',
                        'teams'               => 'Équipes',
                        'role'                => 'Rôle',
                        'resource-permission' => 'Autorisation de ressource',
                        'created-at'          => 'Créé le',
                        'updated-at'          => 'Mis à jour le',
                    ],

                    'filters' => [
                        'resource-permission' => 'Autorisation de ressource',
                        'teams'               => 'Équipes',
                        'roles'               => 'Rôles',
                    ],
                ],
            ],

            'team' => [
                'title' => 'Équipes',

                'navigation' => [
                    'title' => 'Équipes',
                    'group' => 'Paramètres',
                ],

                'form' => [
                    'name' => 'Nom',
                ],

                'table' => [
                    'name' => 'Nom',
                ],
            ],

            'role' => [
                'navigation' => [
                    'title' => 'Rôles',
                    'group' => 'Paramètres',
                ],
            ],
        ],
    ],

    'livewire' => [
        'header' => [
            'sub-heading' => [
                'accept-invitation' => 'Créez votre utilisateur pour accepter une invitation',
            ],
        ],
    ],

    'mail' => [
        'user-invitation' => [
            'subject' => 'Invitation à rejoindre :app',
            'accept'  => [
                'button' => 'Accepter l\'invitation',
            ],
        ],
    ],
];
