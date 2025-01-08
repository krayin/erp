<?php

return [
    'form' => [
        'name'    => 'Name',
        'email'   => 'Email',
        'phone'   => 'Phone',
        'type'    => 'Type',
        'address' => 'Address',
        'city'    => 'City',
        'street1' => 'Street 1',
        'street2' => 'Street 2',
        'state'   => 'State',
        'zip'     => 'Zip',
        'country' => 'Country',
    ],

    'table' => [
        'header-actions' => [
            'create' => [
                'label' => 'Add Address',

                'notification' => [
                    'title' => 'Address created',
                    'body'  => 'The address has been created successfully.',
                ],
            ],
        ],

        'columns' => [
            'type'    => 'Type',
            'address' => 'Address',
            'city'    => 'City',
            'street1' => 'Street 1',
            'street2' => 'Street 2',
            'state'   => 'State',
            'zip'     => 'Zip',
            'country' => 'Country',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Address updated',
                    'body'  => 'The address has been updated successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Address deleted',
                    'body'  => 'The address has been deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Addresses deleted',
                    'body'  => 'The addresses has been deleted successfully.',
                ],
            ],
        ],
    ],
];
