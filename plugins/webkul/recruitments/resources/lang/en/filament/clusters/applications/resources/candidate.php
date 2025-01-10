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
                    'email'     => 'Email Address',
                    'phone'     => 'Phone Number',
                    'linkedin'  => 'LinkedIn Profile',
                    'contact'   => 'Contact'
                ],
            ],

            'additional-details' => [
                'title' => 'Additional Details',

                'fields' => [
                    'company'           => 'Company',
                    'degree'            => 'Degree',
                    'tags'              => 'Tags',
                    'manager'           => 'Manager',
                    'availability-date' => 'Availability Date',

                    'priority-options' => [
                        'low'    => 'Low',
                        'medium' => 'Medium',
                        'high'   => 'High',
                    ],
                ],
            ],

            'status-and-evaluation' => [
                'title' => 'Status',

                'fields' => [
                    'active'     => 'Active',
                    'evaluation' => 'Evaluation',
                ],
            ],

            'communication' => [
                'title' => 'Communication',

                'fields' => [
                    'cc-email'      => 'CC Email',
                    'email-bounced' => 'Email Bounced',
                ],
            ],
        ],
    ],
    'table' => [
        'columns' => [
            'name'       => 'Full Name',
            'tags'       => 'Tags',
            'evaluation' => 'Evaluation',
        ],

        'filters' => [
            'company'  => 'Company',
            'degree'   => 'Degree',
            'priority' => 'Priority',

            'priority-options' => [
                'low'    => 'Low',
                'medium' => 'Medium',
                'high'   => 'High',
            ],

            'status' => 'Status',
        ],

        'actions' => [
            'delete' => [
                'notification' => [
                    'title' => 'Candidate Deleted',
                    'body'  => 'The candidate was successfully deleted.',
                ],
            ],

            'empty-state-actions' => [
                'create' => [
                    'notification' => [
                        'title' => 'Candidate Created',
                        'body'  => 'The candidate was successfully created.',
                    ],
                ],
            ],
        ],
    ],
];
