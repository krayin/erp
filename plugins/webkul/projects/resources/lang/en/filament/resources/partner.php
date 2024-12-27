<?php

return [
    'form' => [
        'sections' => [
            'general' => [
                'title' => 'General',

                'fields' => [
                    'company'    => 'Company',
                    'avatar'     => 'Avatar',
                    'tax-id'     => 'Tax ID',
                    'job-title'  => 'Job Title',
                    'phone'      => 'Phone',
                    'mobile'     => 'Mobile',
                    'email'      => 'Email',
                    'website'    => 'Website',
                    'title'      => 'Title',
                    'name'       => 'Name',
                    'short-name' => 'Short Name',
                    'tags'       => 'Tags',
                ],
            ],
        ],

        'tabs' => [
            'sales-purchase' => [
                'title' => 'Sales and Purchases',

                'fields' => [
                    'responsible'           => 'Responsible',
                    'responsible-hint-text' => 'This is internal salesperson responsible for this customer',
                    'company-id'            => 'Company ID',
                    'company-id-hint-text'  => 'The registry number of the company. Use it if it is different from the Tax ID. It must be unique across all partners of a same country',
                    'reference'             => 'Reference',
                    'industry'              => 'Industry',
                ],
            ],
        ],
    ],
];
