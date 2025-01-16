<?php

return [
    'navigation' => [
        'title' => 'Locations',
        'group' => 'Warehouse Management',
    ],

    'form' => [
        'sections' => [
            'general' => [
                'title'  => 'General',

                'fields' => [
                    'location'                     => 'Location',
                    'location-placeholder'         => 'eg. Spare Stock',
                    'parent-location'              => 'Parent Location',
                    'parent-location-hint-tooltip' => 'The parent location that includes this location. Example : The \'Dispatch Zone\' is the \'Gate 1\' parent location.',
                    'external-notes'               => 'External Notes',
                ],
            ],

            'settings' => [
                'title'  => 'Settings',

                'fields' => [
                    'location-type'                 => 'Location Type',
                    'company'                       => 'Company',
                    'storage-category'              => 'Storage Category',
                    'is-scrap'                      => 'Is a Scrap Location?',
                    'is-scrap-hint-tooltip'         => 'Check this box to allow using this location to put scrapped/damaged goods.',
                    'is-dock'                       => 'Is a Dock Location?',
                    'is-dock-hint-tooltip'          => 'Check this box to allow using this location to put goods that are ready to be shipped.',
                    'is-replenish'                  => 'Is a Replenish Location?',
                    'is-replenish-hint-tooltip'     => 'Activate this function to get all quantities to replenish at this particular location.',
                    'logistics'                     => 'Logistics',
                    'removal-strategy'              => 'Removal Strategy',
                    'removal-strategy-hint-tooltip' => 'Defines the default method used for suggesting the exact location (shelf) where to take the products from, which lot etc. for this location. This method can be enforced at the product category level, and a fallback is made on the parent locations if none is set here.',
                    'cyclic-counting'               => 'Cyclic Counting',
                    'inventory-frequency'           => 'Inventory Frequency',
                    'last-inventory'                => 'Last Inventory',
                    'last-inventory-hint-tooltip'   => 'Date of the last inventory at this location.',
                    'next-expected'                 => 'Next expected',
                    'next-expected-hint-tooltip'    => 'Date for next planned inventory based on cyclic schedule.',
                ],
            ],

            'additional' => [
                'title'  => 'Additional Information',
            ],
        ],
    ],

    'table' => [
        'columns' => [
            'location'         => 'Location',
            'type'             => 'Type',
            'storage-category' => 'Storage Category',
            'company'          => 'Company',
            'deleted-at'       => 'deleted At',
            'created-at'       => 'Created At',
            'updated-at'       => 'Updated At',
        ],

        'groups' => [
            'warehouse'       => 'Warehouse',
            'type'            => 'Type',
            'created-at'      => 'Created At',
            'updated-at'      => 'Updated At',
        ],

        'actions' => [
            'edit' => [
                'notification' => [
                    'title' => 'Location updated',
                    'body'  => 'The location has been updated successfully.',
                ],
            ],

            'restore' => [
                'notification' => [
                    'title' => 'Location restored',
                    'body'  => 'The location has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Location deleted',
                    'body'  => 'The location has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Location force deleted',
                    'body'  => 'The location has been force deleted successfully.',
                ],
            ],
        ],

        'bulk-actions' => [
            'restore' => [
                'notification' => [
                    'title' => 'Locations restored',
                    'body'  => 'The locations has been restored successfully.',
                ],
            ],

            'delete' => [
                'notification' => [
                    'title' => 'Locations deleted',
                    'body'  => 'The locations has been deleted successfully.',
                ],
            ],

            'force-delete' => [
                'notification' => [
                    'title' => 'Locations force deleted',
                    'body'  => 'The locations has been force deleted successfully.',
                ],
            ],
        ],
    ],

    'infolist' => [
        'name'   => 'Name',
        'status' => 'Status',
    ],
];
