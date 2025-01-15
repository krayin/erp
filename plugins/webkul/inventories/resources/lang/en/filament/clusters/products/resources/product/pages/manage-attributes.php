<?php

return [
    'title' => 'Attributes',

    'form' => [
        'date'                   => 'Date',
        'employee'               => 'Employee',
        'description'            => 'Description',
        'time-spent'             => 'Time Spent',
        'time-spent-helper-text' => 'Time spent in hours (Eg. 1.5 hours means 1 hour 30 minutes)',
    ],

    'table' => [
        'description' => 'Warning: adding or deleting attributes will delete and recreate existing variants and lead to the loss of their possible customizations.',

        'header-actions' => [
            'create' => [
                'label' => 'Add Attribute',

                'notification' => [
                    'title' => 'Attribute created',
                    'body'  => 'The attribute has been created successfully.',
                ],
            ],
        ],

        'columns' => [
            'attribute' => 'Attribute',
            'values'    => 'Values',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Attribute updated',
                    'body'  => 'The attribute has been updated successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Attribute deleted',
                    'body'  => 'The attribute has been deleted successfully.',
                ],
            ],
        ],
    ],
];
