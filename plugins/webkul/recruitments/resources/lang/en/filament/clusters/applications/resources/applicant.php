<?php

return [
    'title' => 'Applicant',

    'navigation' => [
        'group' => 'Recruitment',
        'title' => 'Applicants',
    ],

    'form' => [
        'sections' => [
            'general-information' => [
                'title' => 'General Information',

                'fields' => [
                    'evaluation-good' => 'Evaluation: Good',
                    'evaluation-very-good' => 'Evaluation: Very Good',
                    'evaluation-very-excellent' => 'Evaluation: Very Excellent',
                    'hired' => 'Hired',
                    'candidate-name' => 'Candidate name',
                    'email' => 'Emails',
                    'phone' => 'Phone',
                    'linkedin-profile' => 'Linkedin Profile',
                    'recruiter' => 'Recruiter',
                    'interviewer' => 'Interviewer',
                    'tags' => 'Tags',
                    'notes' => 'Notes',
                    'job-position' => 'Job Positions',
                ],
            ],

            'education-and-availability' => [
                'title' => 'Education & Availability',

                'fields' => [
                    'degree' => 'Degree',
                    'availability-date' => 'Availability Date'
                ],
            ],

            'department' => [
                'title' => 'Department',
            ],

            'salary' => [
                'title' => 'Expected & Proposed Salary',

                'fields' => [
                    'expected-salary' => 'Expected Salary',
                    'proposed-salary' => 'Proposed Salary',
                ],
            ],

            'source-and-medium' => [
                'title' => 'Source & Medium',

                'fields' => [
                    'source' => 'Source',
                    'medium' => 'Medium',
                ]
            ]
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
                    'title' => 'Applicant Deleted',
                    'body'  => 'The applicant was successfully deleted.',
                ],
            ],

            'empty-state-actions' => [
                'create' => [
                    'notification' => [
                        'title' => 'Applicant Created',
                        'body'  => 'The applicant was successfully created.',
                    ],
                ],
            ],
        ],
    ],

    'infolist' => [
        'sections' => [
            'general-information' => [
                'title' => 'General Information',

                'entries' => [
                    'evaluation-good' => 'Evaluation: Good',
                    'evaluation-very-good' => 'Evaluation: Very Good',
                    'evaluation-very-excellent' => 'Evaluation: Very Excellent',
                    'hired' => 'Hired',
                    'candidate-name' => 'Candidate name',
                    'email' => 'Emails',
                    'phone' => 'Phone',
                    'linkedin-profile' => 'Linkedin Profile',
                    'recruiter' => 'Recruiter',
                    'interviewer' => 'Interviewer',
                    'tags' => 'Tags',
                    'notes' => 'Notes',
                    'job-position' => 'Job Positions',
                ],
            ],

            'education-and-availability' => [
                'title' => 'Education & Availability',

                'entries' => [
                    'degree' => 'Degree',
                    'availability-date' => 'Availability Date'
                ],
            ],

            'department' => [
                'title' => 'Department',
            ],

            'salary' => [
                'title' => 'Expected & Proposed Salary',

                'entries' => [
                    'expected-salary' => 'Expected Salary',
                    'proposed-salary' => 'Proposed Salary',
                ],
            ],

            'source-and-medium' => [
                'title' => 'Source & Medium',

                'entries' => [
                    'source' => 'Source',
                    'medium' => 'Medium',
                ]
            ]
        ],
    ],
];
