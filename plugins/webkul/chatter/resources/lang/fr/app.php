<?php

return [
    'enums' => [
        'activity-type' => [
            'to-do'    => 'À faire',
            'email'    => 'Courriel',
            'call'     => 'Appel',
            'meeting'  => 'Réunion',
        ],

        'task-status' => [
            'completed'   => 'Terminé',
            'pending'     => 'En attente',
            'in-progress' => 'En cours',
        ],
    ],

    'filament' => [
        'actions' => [
            'chatter' => [
                'activity' => [
                    'form' => [
                        'activity-type'          => 'Type d\'activité',
                        'due-date'               => 'Date d\'échéance',
                        'summary'                => 'Résumé',
                        'assigned-to'            => 'Assigné à',
                        'type-your-message-here' => 'Tapez votre message ici...',
                    ],

                    'action' => [
                        'label'               => 'Planifier une activité',
                        'modal-submit-action' => [
                            'title' => 'Planifier',
                        ],
                        'notification' => [
                            'success' => [
                                'title' => 'Activité planifiée',
                                'body'  => 'Votre activité a été planifiée avec succès.',
                            ],
                            'danger' => [
                                'title' => 'Échec de la planification de l\'activité',
                                'body'  => 'Une erreur s\'est produite lors de la planification de votre activité.',
                            ],
                        ],
                    ],
                ],

                'file' => [
                    'form' => [
                        'file' => 'Fichier',
                    ],

                    'action' => [
                        'label'               => 'Ajouter des fichiers',
                        'modal-submit-action' => [
                            'title' => 'Ajouter des fichiers',
                        ],
                        'notification' => [
                            'success' => [
                                'title' => 'Fichier envoyé',
                                'body'  => 'Votre fichier a été envoyé avec succès.',
                            ],
                            'danger' => [
                                'title' => 'Échec de l\'envoi du fichier',
                                'body'  => 'Une erreur s\'est produite lors de l\'envoi de votre message.',
                            ],
                        ],
                    ],
                ],

                'follower' => [
                    'modal' => [
                        'heading' => 'Abonnés',
                    ],
                ],

                'log' => [
                    'label'               => 'Consigner une note',
                    'modal-submit-action' => [
                        'log' => 'Consigner',
                    ],
                    'form' => [
                        'type-your-message-here' => 'Tapez votre message ici...',
                    ],

                    'notification' => [
                        'success' => [
                            'title' => 'Note consignée',
                            'body'  => 'Votre note a été ajoutée avec succès.',
                        ],
                        'danger' => [
                            'title' => 'Note non consignée',
                            'body'  => 'Une erreur s\'est produite lors de l\'ajout de votre note.',
                        ],
                    ],
                ],

                'message' => [
                    'form' => [
                        'type-your-message-here' => 'Tapez votre message ici...',
                    ],
                    'label'               => 'Envoyer un message',
                    'modal-submit-action' => [
                        'title' => 'Envoyer',
                    ],

                    'notification' => [
                        'success' => [
                            'title' => 'Message envoyé',
                            'body'  => 'Votre message a été envoyé avec succès.',
                        ],
                        'danger' => [
                            'title' => 'Échec de l\'envoi du message',
                            'body'  => 'Une erreur s\'est produite lors de l\'envoi de votre message.',
                        ],
                    ],
                ],

                'action' => [
                    'modal' => [
                        'label'       => 'Discussion',
                        'description' => 'Ajouter des messages, des notes, des activités, des fichiers joints, et plus encore.',
                    ],
                ],
            ],
        ],

        'resources' => [
            'task' => [
                'label' => 'Tâches',

                'pages' => [
                    'list' => [
                        'tabs' => [
                            'my-tasks'      => 'Mes tâches',
                            'pending-tasks' => 'Tâches en attente',
                        ],
                    ],
                ],

                'form' => [
                    'section' => [
                        'task-details' => [
                            'title'       => 'Détails de la tâche',
                            'description' => 'Fournissez un titre et une description pour la tâche',
                            'schema'      => [
                                'title'       => 'Titre de la tâche',
                                'description' => 'Description de la tâche',
                            ],
                        ],

                        'task-status' => [
                            'title'       => 'Statut de la tâche',
                            'description' => 'Spécifiez le statut et la date d\'échéance de la tâche',
                            'schema'      => [
                                'status'   => 'Statut de la tâche',
                                'due-date' => 'Date d\'échéance',
                            ],
                        ],

                        'task-assignment' => [
                            'title'       => 'Assignation de la tâche',
                            'description' => 'Gérez la création et l\'assignation de la tâche',
                            'schema'      => [
                                'created-by'  => 'Créé par',
                                'assigned-to' => 'Assigné à',
                                'followers'   => 'Abonnés',
                            ],
                        ],

                        'additional-information' => [
                            'title'       => 'Informations supplémentaires',
                            'description' => 'Fournissez des informations supplémentaires sur la tâche',
                            'schema'      => [
                                'priority' => 'Priorité',
                                'tags'     => 'Étiquettes',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'title'           => 'Titre',
                        'status'          => 'Statut',
                        'due-date'        => 'Date d\'échéance',
                        'created-by'      => 'Créé par',
                        'assigned-to'     => 'Assigné à',
                        'created-at'      => 'Créé le',
                        'updated-at'      => 'Mis à jour le',
                        'followers-count' => 'Nombre d\'abonnés',
                    ],

                    'filters' => [
                        'status'      => 'Statut',
                        'created-by'  => 'Créé par',
                        'assigned-to' => 'Assigné à',
                    ],
                ],

                'infolist' => [
                    'section' => [
                        'task-details' => [
                            'title'       => 'Détails de la tâche',
                            'description' => 'Voir le titre et la description de la tâche',
                            'schema'      => [
                                'title'       => 'Titre de la tâche',
                                'description' => 'Description de la tâche',
                            ],
                        ],
                        'task-status' => [
                            'title'       => 'Statut de la tâche',
                            'description' => 'Spécifiez le statut et la date d\'échéance de la tâche',
                            'schema'      => [
                                'status'   => 'Statut de la tâche',
                                'due_date' => 'Date d\'échéance',
                            ],
                        ],
                        'task-assignment' => [
                            'title'       => 'Assignation de la tâche',
                            'description' => 'Gérez la création et l\'assignation de la tâche',
                            'schema'      => [
                                'created_by'  => 'Créé par',
                                'assigned_to' => 'Assigné à',
                                'followers'   => 'Abonnés',
                            ],
                        ],
                        'additional-information' => [
                            'title'       => 'Informations supplémentaires',
                            'description' => 'Ce sont les informations des champs personnalisés',
                        ],
                    ],
                ],

                'navigation' => [
                    'title' => 'Tâches',
                ],
            ],
        ],
    ],

    'livewire' => [
        'chatter_panel' => [
            'actions' => [
                'follower' => [
                    'add_success'    => 'Abonné ajouté avec succès.',
                    'remove_success' => 'Abonné supprimé avec succès.',
                    'error'          => 'Erreur lors de la gestion de l\'abonné.',
                ],
                'delete_chat' => [
                    'confirmation' => 'Êtes-vous sûr de vouloir supprimer cette discussion ?',
                ],
            ],
            'placeholders' => [
                'no_record_found' => 'Aucun enregistrement trouvé.',
                'loading'         => 'Chargement de la discussion...',
            ],
            'notifications' => [
                'success' => 'Succès',
                'error'   => 'Erreur',
            ],
            'search' => [
                'placeholder' => 'Rechercher des utilisateurs par nom ou email',
            ],
        ],

        'follower' => [
            'actions' => [
                'toggle' => [
                    'add_success'    => ':name a été ajouté(e) comme abonné(e) avec succès.',
                    'remove_success' => ':name a été retiré(e) des abonnés avec succès.',
                    'error'          => 'Erreur lors de la gestion de l\'abonné',
                ],
            ],
        ],
    ],

    'trait' => [
        'activity-log-failed' => [
            'events' => [
                'created'      => 'Un nouveau :model a été créé',
                'updated'      => 'Le :model a été mis à jour',
                'deleted'      => 'Le :model a été supprimé',
                'soft-deleted' => 'Le :model a été supprimé temporairement',
                'hard-deleted' => 'Le :model a été définitivement supprimé',
                'restored'     => 'Le :model a été restauré',
            ],
            'attributes' => [
                'unassigned' => 'Non assigné',
            ],
            'errors' => [
                'user-fetch-failed'   => 'Impossible de récupérer l\'utilisateur pour le champ :field',
                'activity-log-failed' => 'Échec de la création du journal d\'activité : :message',
            ],
        ],
    ],

    'views' => [
        'filament' => [
            'infolists' => [
                'components' => [
                    'content-text-entry' => [
                        'attachments'      => 'Pièces jointes',
                        'activity-details' => 'Détails de l\'activité',
                        'created-by'       => 'Créé par',
                        'summary'          => 'Résumé',
                        'due-date'         => 'Date d\'échéance',
                        'assigned-to'      => 'Assigné à',
                        'changes-made'     => 'Modifications effectuées',
                        'modified'         => 'Le champ <b>:field</b> a été',
                    ],

                    'title-text-entry' => [
                        'tooltip' => [
                            'delete' => 'Supprimer le commentaire',
                        ],
                    ],
                ],
            ],
        ],

        'livewire' => [
            'current-followers'       => 'Abonnés actuels',
            'no-followers-yet'        => 'Pas encore d\'abonnés.',
            'add-followers'           => 'Ajouter des abonnés',
            'add'                     => 'Ajouter',
            'adding'                  => 'Ajout en cours...',
            'user-not-found-matching' => 'Aucun utilisateur trouvé correspondant à ":query"',
        ],
    ],
];
