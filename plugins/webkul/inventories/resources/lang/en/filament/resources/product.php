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
                    'name'             => 'Name',
                    'name-placeholder' => 'eg. T-shirt',
                    'description'      => 'Description',
                    'tags'             => 'Tags',
                ],
            ],

            'images' => [
                'title' => 'Images',
            ],

            'settings' => [
                'title' => 'Settings',

                'fields' => [
                    'type'      => 'Type',
                    'reference' => 'Reference',
                    'barcode'   => 'Barcode',
                    'category'  => 'Category',
                    'company'   => 'Company',
                ],
            ],

            'pricing' => [
                'title' => 'Pricing',

                'fields' => [
                    'price' => 'Price',
                    'cost'  => 'Cost',
                ],
            ],

            'inventory' => [
                'title' => 'Inventory',

                'fields' => [
                ],

                'fieldsets' => [
                    'tracking' => [
                        'title' => 'Tracking',

                        'fields' => [
                            'track-inventory'              => 'Track Inventory',
                            'track-inventory-hint-tooltip' => 'A storable product is a product for which you manage stock.',
                            'track-by'                     => 'Track By',
                            'expiration-date'              => 'Expiration Date',
                            'expiration-date-hint-tooltip' => 'When this box is ticked, you have the possibility to specify dates to manage product expiration, on the product and on the corresponding lot/serial numbers',
                        ],
                    ],

                    'operation' => [
                        'title' => 'Operations',

                        'fields' => [
                            'routes'              => 'Routes',
                            'routes-hint-tooltip' => 'Depending on the modules installed, this will allow you to define the route of the product: whether it will be bought, manufactured, replenished on order, etc.',
                        ],
                    ],

                    'logistics' => [
                        'title' => 'Logistics',

                        'fields' => [
                            'responsible'              => 'Responsible',
                            'responsible-hint-tooltip' => 'Delivery lead time, in days. It\'s the number of days, promised to the customer, between the confirmation of the sales order and the delivery.',
                            'weight'                   => 'Weight',
                            'volume'                   => 'Volume',
                            'sale-delay'               => 'Customer Lead Time (Days)',
                            'sale-delay-hint-tooltip'  => 'Delivery lead time, in days. It\'s the number of days, promised to the customer, between the confirmation of the sales order and the delivery.',
                        ],
                    ],

                    'traceability' => [
                        'title' => 'Traceability',

                        'fields' => [
                            'expiration-date'               => 'Expiration Date (Days)',
                            'expiration-date-hint-tooltip'  => 'When this box is ticked, you have the possibility to specify dates to manage product expiration, on the product and on the corresponding lot/serial numbers',
                            'best-before-date'              => 'Best Before Date (Days)',
                            'best-before-date-hint-tooltip' => 'Number of days before the Expiration Date after which the goods starts deteriorating, without being dangerous yet. It will be computed on the lot/serial number.',
                            'removal-date'                  => 'Removal Date (Days)',
                            'removal-date-hint-tooltip'     => 'Number of days before the Expiration Date after which the goods should be removed from the stock. It will be computed on the lot/serial number.',
                            'alert-date'                    => 'Alert Date (Days)',
                            'alert-date-hint-tooltip'       => 'Number of days before the Expiration Date after which an alert should be raised on the lot/serial number. It will be computed on the lot/serial number.',
                        ],
                    ],
                ],
            ],

            'additional' => [
                'title' => 'Additional',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'name'        => 'Name',
            'images'      => 'Images',
            'type'        => 'Type',
            'reference'   => 'Reference',
            'responsible' => 'Responsible',
            'barcode'     => 'Barcode',
            'category'    => 'Category',
            'company'     => 'Company',
            'price'       => 'Price',
            'cost'        => 'Cost',
            'tags'        => 'Tags',
            'deleted-at'  => 'Deleted At',
            'created-at'  => 'Created At',
            'updated-at'  => 'Updated At',
        ],

        'groups' => [
            'type'       => 'Type',
            'category'   => 'Category',
            'created-at' => 'Created At',
        ],

        'filters' => [
            'name'        => 'Name',
            'type'        => 'Type',
            'reference'   => 'Reference',
            'barcode'     => 'Barcode',
            'category'    => 'Category',
            'company'     => 'Company',
            'price'       => 'Price',
            'cost'        => 'Cost',
            'is-favorite' => 'Is Favorite',
            'weight'      => 'Weight',
            'volume'      => 'Volume',
            'tags'        => 'Tags',
            'responsible' => 'Responsible',
            'created-at'  => 'Created At',
            'updated-at'  => 'Updated At',
            'creator'     => 'Creator',
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

    'infolist' => [
        'sections' => [
            'general' => [
                'title' => 'General',

                'entries' => [
                    'name'             => 'Name',
                    'name-placeholder' => 'eg. T-shirt',
                    'description'      => 'Description',
                    'tags'             => 'Tags',
                ],
            ],

            'images' => [
                'title' => 'Images',

                'entries' => [
                ],
            ],

            'settings' => [
                'title' => 'Settings',

                'entries' => [
                    'type'      => 'Type',
                    'reference' => 'Reference',
                    'barcode'   => 'Barcode',
                    'category'  => 'Category',
                    'company'   => 'Company',
                ],
            ],

            'pricing' => [
                'title' => 'Pricing',

                'entries' => [
                    'price' => 'Price',
                    'cost'  => 'Cost',
                ],
            ],

            'inventory' => [
                'title' => 'Inventory',

                'entries' => [
                ],

                'fieldsets' => [
                    'tracking' => [
                        'title' => 'Tracking',

                        'entries' => [
                            'track-inventory' => 'Track Inventory',
                            'track-by'        => 'Track By',
                            'expiration-date' => 'Expiration Date',
                        ],
                    ],

                    'operation' => [
                        'title' => 'Operations',

                        'entries' => [
                            'routes' => 'Routes',
                        ],
                    ],

                    'logistics' => [
                        'title' => 'Logistics',

                        'entries' => [
                            'responsible' => 'Responsible',
                            'weight'      => 'Weight',
                            'volume'      => 'Volume',
                            'sale-delay'  => 'Customer Lead Time (Days)',
                        ],
                    ],

                    'traceability' => [
                        'title' => 'Traceability',

                        'entries' => [
                            'expiration-date'  => 'Expiration Date (Days)',
                            'best-before-date' => 'Best Before Date (Days)',
                            'removal-date'     => 'Removal Date (Days)',
                            'alert-date'       => 'Alert Date (Days)',
                        ],
                    ],
                ],
            ],

            'record-information' => [
                'title' => 'Record Information',

                'entries' => [
                    'created-at' => 'Created At',
                    'created-by' => 'Created By',
                    'updated-at' => 'Updated At',
                ],
            ],
        ],
    ],
];
