<?php

return [
    'navigation' => [
        'label' => 'Champs personnalisés',
        'group' => 'Paramètres',
    ],

    'model-label' => 'Champs',

    'actions' => [
        'create-view' => [
            'form' => [
                'name'                  => 'Nom',
                'color'                 => 'Couleur',
                'icon'                  => 'Icône',
                'add-to-favorites'      => 'Ajouter aux favoris',
                'add-to-favorites-help' => 'Ajoutez ce filtre à vos favoris',
                'make-public'           => 'Rendre public',
                'make-public-help'      => 'Rendez ce filtre disponible pour tous les utilisateurs',
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
                'add-to-favorites-help' => 'Ajoutez ce filtre à vos favoris',
                'make-public'           => 'Rendre public',
                'make-public-help'      => 'Rendez ce filtre disponible pour tous les utilisateurs',
                'options'               => [
                    'danger'  => 'Danger',
                    'gray'    => 'Gris',
                    'info'    => 'Information',
                    'success' => 'Succès',
                    'warning' => 'Avertissement',
                ],

                'notification' => [
                    'created' => 'Vue modifiée avec succès',
                ],

                'modal' => [
                    'title' => 'Modifier la vue',
                ],
            ],
        ],
    ],

    'views' => [
        'default'                 => 'Par défaut',
        'title'                   => 'Vues',
        'apply-view'              => 'Appliquer la vue',
        'add-to-favorites'        => 'Ajouter aux favoris',
        'remove-from-favorites'   => 'Retirer des favoris',
        'delete-view'             => 'Supprimer la vue',
        'replace-view'            => 'Remplacer la vue',
        'reset'            => 'Réinitialiser',
        'favorites-views'  => 'Vues favorites',
        'saved-views'      => 'Vues enregistrées',
        'preset-views'     => 'Vues prédéfinies',
    ],
];
