<?php

return [
    'form' => [
        'fields' => [
            'name' => 'Name',
            'color' => 'Color',
            'extra-price' => 'Extra Price',
        ],
    ],

    'table' => [
        'header-actions' => [
            'create' => [
                'label' => 'Add Option',

                'notification' => [
                    'title' => 'Option created',
                    'body'  => 'The option has been created successfully.',
                ],
            ],
        ],

        'columns' => [
            'name' => 'Name',
            'color' => 'Color',
            'extra-price' => 'Extra Price',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Option updated',
                    'body'  => 'The option has been updated successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Option deleted',
                    'body'  => 'The option has been deleted successfully.',
                ],
            ],
        ],
    ],
];
