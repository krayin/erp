<?php

return [
    'navigation' => [
        'title' => 'Products',
        'group' => 'Inventory',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title' => 'General',

                'fields' => [
                    'receive-from' => 'Receive From',
                    'contact' => 'Contact',
                    'delivery-address' => 'Delivery Address',
                    'operation-type' => 'Operation Type',
                    'source-location' => 'Source Location',
                    'destination-location' => 'Destination Location',
                    'external-notes' => 'External Notes',
                ],
            ],

            'additional' => [
                'title' => 'Additional',

                'fields' => [
                    'responsible' => 'Responsible',
                    'shipping-policy' => 'Shipping Policy',
                    'shipping-policy-hint-tooltip' => 'It specifies goods to be deliver partially or all at once.',
                    'scheduled-at' => 'Scheduled At',
                    'scheduled-at-hint-tooltip' => 'Scheduled time for the first part of the shipment to be processed. Setting manually a value here would set it as expected date for all the stock moves.',
                    'source-document' => 'Source Document',
                    'source-document-hint-tooltip' => 'Reference of the document',
                ],
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'reference' => 'Reference',
            'from'      => 'From',
            'to'        => 'To',
            'contact'   => 'Contact',
            'responsible' => 'Responsible',
            'scheduled-at' => 'Scheduled At',
            'deadline'  => 'Deadline',
            'closed-at' => 'Closed At',
            'source-document' => 'Source Document',
            'operation-type' => 'Operation Type',
            'company'   => 'Company',
            'state'    => 'State',
            'deleted-at'  => 'Deleted At',
            'created-at'  => 'Created At',
            'updated-at'  => 'Updated At',
        ],

        'groups' => [
            'created-at' => 'Created At',
        ],

        'filters' => [
        ],

        'actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Product restored',
                    'body'  => 'The product has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Product deleted',
                    'body'  => 'The product has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Product force deleted',
                    'body'  => 'The product has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Products restored',
                    'body'  => 'The products has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Products deleted',
                    'body'  => 'The products has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Products force deleted',
                    'body'  => 'The products has been force deleted successfully.',
                ],
            ],
        ],
    ],
];
