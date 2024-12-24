<?php

return [
    'filament' => [
        'resources' => [
            'project' => [
                'navigation' => [
                    'title' => 'Projects',
                    'group' => 'Project',
                ],

                'pages' => [
                    'create' => [
                    ],

                    'edit' => [
                        'header-actions' => [
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                        ],
                    ],
                ],

                'form' => [
                    'sections' => [
                    ],
                ],

                'table' => [
                    'columns' => [
                    ],

                    'filters' => [
                    ],
                ],
            ],
            'task' => [
                'title' => 'Tasks',

                'navigation' => [
                    'title' => 'Tasks',
                    'group' => 'Project',
                ],

                'pages' => [
                    'create' => [
                    ],

                    'edit' => [
                        'header-actions' => [
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                        ],
                    ],
                ],

                'form' => [
                    'sections' => [
                        'general' => [
                            'title' => 'General',

                            'fields' => [
                                'title' => 'Title',
                                'title-placeholder' => 'Task Title...',
                                'tags' => 'Tags',
                                'name' => 'Name',
                                'description' => 'Description',
                                'project' => 'Project',
                                'status' => 'Status',
                                'start_date' => 'Start Date',
                                'end_date' => 'End Date',
                            ],

                            'additional' => [
                                'title' => 'Additional Information',
                            ],

                            'settings' => [
                                'title' => 'Settings',

                                'fields' => [
                                    'project' => 'Project',
                                    'milestone' => 'Milestone',
                                    'milestone-hint-text' => 'Deliver your services automatically when a milestone is reached by linking it to a sales order item.',
                                    'name' => 'Name',
                                    'deadline' => 'Deadline',
                                    'is-completed' => 'Is Completed',
                                    'customer' => 'Customer',
                                    'assignees' => 'Assignees',
                                    'allocated-hours' => 'Allocated Hours',
                                ],
                            ]
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'id' => 'ID',
                        'priority' => 'Priority',
                        'state' => 'State',
                        'new-state' => 'New State',
                        'update-state' => 'Update State',
                        'title' => 'Title',
                        'project' => 'Project',
                        'project-placeholder' => 'Private Task',
                        'milestone' => 'Milestone',
                        'customer' => 'Customer',
                        'assignees' => 'Assignees',
                        'allocated-time' => 'Allocated Time',
                        'time-spent' => 'Time Spent',
                        'time-remaining' => 'Time Remaining',
                        'progress' => 'Progress',
                        'deadline' => 'Deadline',
                        'tags' => 'Tags',
                        'stage' => 'Stage',
                    ],

                    'groups' => [
                        'state' => 'State',
                        'project' => 'Project',
                        'milestone' => 'Milestone',
                        'customer' => 'Customer',
                        'deadline' => 'Deadline',
                        'stage' => 'Stage',
                        'created-at' => 'Created At',
                    ],

                    'filters' => [
                    ],
                ],
            ],
        ],
    ],
];
