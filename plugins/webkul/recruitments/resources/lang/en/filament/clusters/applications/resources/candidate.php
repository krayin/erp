<?php

return [
    'title' => 'Candidate',

    'navigation' => [
        'group' => 'Recruitment',
        'title' => 'Candidates',
    ],

    'form' => [
        'sections' => [
            'basic-information' => [
                'title' => 'Basic Information',

                'fields' => [
                    'full-name' => 'Full Name',
                    'email' => 'Email Address',
                    'phone' => 'Phone Number',
                    'linkedin' => 'LinkedIn Profile',
                ],
            ],

            'additional-details' => [
                'title' => 'Additional Details',

                'fields' => [
                    'company' => 'Company',
                    'degree' => 'Degree',
                    'availability-date' => 'Availability Date',

                    'priority-options' => [
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                    ],
                ],
            ],

            'status' => [
                'title' => 'Status',

                'fields' => [
                    'active' => 'Active',
                    'label-color' => 'Label Color',
                ],
            ],

            'communication' => [
                'title' => 'Communication',

                'fields' => [
                    'cc-email' => 'CC Email',
                    'email-bounced' => 'Email Bounced',
                ],
            ],
        ],
    ],
    'table' => [
        'columns' => [
            'name' => 'Full Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'company' => 'Company',
            'degree' => 'Degree',
            'availability' => 'Availability Date',

            'priority-options' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ],

            'status' => 'Status',
            'label' => 'Label Color',
        ],
        'filters' => [
            'company' => 'Company',
            'degree' => 'Degree',
            'priority' => 'Priority',

            'priority-options' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
            ],

            'status' => 'Status',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Candidate Deleted',
                    'body' => 'The candidate was successfully deleted.',
                ],
            ],

            'empty-state-actions' => [
                'create' => [
                    'notification' => [
                        'title' => 'Candidate Created',
                        'body' => 'The candidate was successfully created.',
                    ],
                ],
            ],
        ],
    ],
];
