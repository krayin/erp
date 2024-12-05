<?php

return [
    'filament' => [
        'actions' => [
            'create-view' => [
                'form' => [
                    'name'                  => 'Nom',
                    'color'                 => 'Couleur',
                    'icon'                  => 'Icône',
                    'add-to-favorites'      => 'Ajouter aux favoris',
                    'add-to-favorites-help' => 'Ajouter ce filtre à vos favoris',
                    'make-public'           => 'Rendre public',
                    'make-public-help'      => 'Rendre ce filtre disponible pour tous les utilisateurs',
                    'options'               => [
                        'danger'  => 'Danger',
                        'gray'    => 'Gris',
                        'info'    => 'Information',
                        'success' => 'Succès',
                        'warning' => 'Avertissement',
                    ],

                    'notification' => [
                        'created' => 'Vue créée avec succès',
                    ],

                    'modal' => [
                        'title' => 'Enregistrer la vue',
                    ],
                ],
            ],

            'edit-view' => [
                'form' => [
                    'name'                  => 'Nom',
                    'color'                 => 'Couleur',
                    'icon'                  => 'Icône',
                    'add-to-favorites'      => 'Ajouter aux favoris',
                    'add-to-favorites-help' => 'Ajouter ce filtre à vos favoris',
                    'make-public'           => 'Rendre public',
                    'make-public-help'      => 'Rendre ce filtre disponible pour tous les utilisateurs',
                    'options'               => [
                        'danger'  => 'Danger',
                        'gray'    => 'Gris',
                        'info'    => 'Information',
                        'success' => 'Succès',
                        'warning' => 'Avertissement',
                    ],

                    'notification' => [
                        'created' => 'Vue créée avec succès',
                    ],

                    'modal' => [
                        'title' => 'Modifier la vue',
                    ],
                ],
            ],
        ],

        'traits' => [
            'has-table-views' => [
                'title'                 => 'Vues',
                'default'               => 'Défaut',
                'apply-view'            => 'Appliquer la vue',
                'add-to-favorites'      => 'Ajouter aux favoris',
                'remove-from-favorites' => 'Retirer des favoris',
                'delete-view'           => 'Supprimer la vue',
                'replace-view'          => 'Remplacer la vue',
            ],
        ],
    ],

    'views' => [
        'component' => [
            'tables' => [
                'table-views' => [
                    'title'           => 'Vues',
                    'reset'           => 'Réinitialiser',
                    'favorites-views' => 'Vues favorites',
                    'saved-views'     => 'Vues enregistrées',
                    'preset-views'    => 'Vues prédéfinies',
                ],
            ],
        ],
    ],
];
