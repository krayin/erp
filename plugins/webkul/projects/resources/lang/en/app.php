<?php

return [
    'filament' => [
        'clusters' => [
            'configurations' => [
                'navigation' => [
                    'title' => 'Configurations',
                    'group' => 'Project',
                ],

                'resources' => [
                    'activity-plan' => [
                        'navigation' => [
                            'title' => 'Activity Plans',
                        ],

                        'form' => [
                            'name' => 'Name',
                            'status' => 'Status',
                        ],

                        'table' => [
                            'columns' => [
                                'name' => 'Name',
                                'status' => 'Status',
                                'created-at' => 'Created At',
                                'updated-at' => 'Updated At',
                            ],

                            'groups' => [
                                'name' => 'Name',
                                'status' => 'Status',
                                'created-at' => 'Created At',
                                'updated-at' => 'Updated At',
                            ],

                            'actions' => [
                                'restore' => [
                                    'notification' => [
                                        'title' => 'Activity Plan restored',
                                        'body' => 'The activity plan has been restored successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Activity Plan deleted',
                                        'body' => 'The activity plan has been deleted successfully.',
                                    ],
                                ],

                                'force-delete' => [
                                    'notification' => [
                                        'title' => 'Activity Plan force deleted',
                                        'body' => 'The activity plan has been force deleted successfully.',
                                    ],
                                ],
                            ],

                            'bulk-actions' => [
                                'restore' => [
                                    'notification' => [
                                        'title' => 'Activity Plans restored',
                                        'body' => 'The activity plans has been restored successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Activity Plans deleted',
                                        'body' => 'The activity plans has been deleted successfully.',
                                    ],
                                ],

                                'force-delete' => [
                                    'notification' => [
                                        'title' => 'Activity Plans force deleted',
                                        'body' => 'The activity plans has been force deleted successfully.',
                                    ],
                                ],
                            ],
                        ],

                        'infolist' => [
                            'name' => 'Name',
                            'status' => 'Status',
                        ],

                        'pages' => [
                            'edit' => [
                                'notification' => [
                                    'title' => 'Activity Plan updated',
                                    'body' => 'The activity plan has been updated successfully.',
                                ],

                                'header-actions' => [
                                    'delete' => [
                                        'notification' => [
                                            'title' => 'Activity Plan deleted',
                                            'body' => 'The activity plan has been deleted successfully.',
                                        ],
                                    ],
                                ],
                            ],

                            'list' => [
                                'tabs' => [
                                    'all' => 'All',
                                    'archived' => 'Archived',
                                ],

                                'header-actions' => [
                                    'delete' => [
                                        'notification' => [
                                            'title' => 'Activity Plan deleted',
                                            'body' => 'The activity plan has been deleted successfully.',
                                        ],
                                    ],
                                ],
                            ],
                        ],

                        'relation-manager' => [

                        ],
                    ],

                    'milestone' => [
                        'navigation' => [
                            'title' => 'Milestones',
                        ],

                        'form' => [
                            'name' => 'Name',
                            'deadline' => 'Deadline',
                            'is-completed' => 'Is Completed',
                            'project' => 'Project',
                        ],

                        'table' => [
                            'columns' => [
                                'name' => 'Name',
                                'deadline' => 'Deadline',
                                'is-completed' => 'Is Completed',
                                'completed-at' => 'Completed At',
                                'project' => 'Project',
                                'creator' => 'Creator',
                                'created-at' => 'Created At',
                                'updated-at' => 'Updated At',
                            ],

                            'groups' => [
                                'name' => 'Name',
                                'is-completed' => 'Is Completed',
                                'project' => 'Project',
                                'created-at' => 'Created At',
                            ],

                            'filters' => [
                                'is-completed' => 'Is Completed',
                                'project' => 'Project',
                                'creator' => 'Creator',
                            ],
                        ],
                    ],
                ],
            ],
        ],

        'resources' => [
            'project' => [
                'navigation' => [
                    'title' => 'Projects',
                    'group' => 'Project',
                ],

                'global-search' => [
                    'project-manager' => 'Project Manager',
                    'customer'        => 'Customer'
                ],

                'form' => [
                    'sections' => [
                        'general' => [
                            'title' => 'General',

                            'fields' => [
                                'name'             => 'Name',
                                'name-placeholder' => 'Project Name...',
                                'description'      => 'Description',
                            ],
                        ],

                        'additional' => [
                            'title' => 'Additional Information',

                            'fields' => [
                                'project-manager' => 'Project Manager',
                                'customer'        => 'Customer',
                                'start-date'      => 'Start Date',
                                'end-date'        => 'End Date',
                                'allocated-hours' => 'Allocated Hours',
                                'allocated-hours-helper-text' => 'In hours (Eg. 1.5 hours means 1 hour 30 minutes)',
                                'tags' => 'Tags',
                            ],
                        ],

                        'settings' => [
                            'title' => 'Settings',

                            'fields' => [
                                'visibility' => 'Visibility',
                                'visibility-hint-tooltip' => 'Grant employees access to your project or tasks by adding them as followers. Employees automatically get access to the tasks they are assigned to.',
                                'private-description' => 'Invited internal users only.',
                                'internal-description' => 'All internal users can see.',
                                'public-description' => 'Invited portal users and all internal users.',
                                'time-management' => 'Time Management',
                                'allow-timesheets' => 'Allow Timesheets',
                                'allow-timesheets-helper-text' => 'Log time on tasks and track progress',
                                'task-management' => 'Task Management',
                                'allow-milestones' => 'Allow Milestones',
                                'allow-milestones-helper-text' => 'Track major progress points that must be reached to achieve success',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'name' => 'Name',
                        'customer' => 'Customer',
                        'start-date' => 'Start Date',
                        'end-date' => 'End Date',
                        'planned-date' => 'Planned Date',
                        'remaining-hours' => 'Remaining Hours',
                        'project-manager' => 'Project Manager',
                    ],

                    'groups' => [
                        'stage' => 'Stage',
                        'project-manager' => 'Project Manager',
                        'customer' => 'Customer',
                        'created-at' => 'Created At',
                    ],

                    'filters' => [
                        'name' => 'Name',
                        'visibility' => 'Visibility',
                        'start-date' => 'Start Date',
                        'end-date' => 'End Date',
                        'allow-timesheets' => 'Allow Timesheets',
                        'allow-milestones' => 'Allow Milestones',
                        'allocated-hours' => 'Allocated Hours',
                        'created-at' => 'Created At',
                        'updated-at' => 'Updated At',
                        'stage' => 'Stage',
                        'customer' => 'Customer',
                        'project-manager' => 'Project Manager',
                        'company' => 'Company',
                        'creator' => 'Creator',
                        'tags' => 'Tags',
                    ],

                    'actions' => [
                        'tasks' => ':count Tasks',
                        'milestones' => ':completed milestones completed out of :all',

                        'restore' => [
                            'notification' => [
                                'title' => 'Project restored',
                                'body' => 'The project has been restored successfully.',
                            ],
                        ],

                        'delete' => [
                            'notification' => [
                                'title' => 'Project deleted',
                                'body' => 'The project has been deleted successfully.',
                            ],
                        ],

                        'force-delete' => [
                            'notification' => [
                                'title' => 'Project force deleted',
                                'body' => 'The project has been force deleted successfully.',
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
                                'name-placeholder' => 'Project Name...',
                                'description'      => 'Description',
                            ],
                        ],

                        'additional' => [
                            'title' => 'Additional Information',

                            'entries' => [
                                'project-manager' => 'Project Manager',
                                'customer'        => 'Customer',
                                'project-timeline' => 'Project Timeline',
                                'allocated-hours' => 'Allocated Hours',
                                'allocated-hours-suffix' => ' Hours',
                                'remaining-hours' => 'Remaining Hours',
                                'remaining-hours-suffix' => ' Hours',
                                'current-stage' => 'Current Stage',
                                'tags' => 'Tags',
                            ],
                        ],

                        'statistics' => [
                            'title' => 'Statistics',

                            'entries' => [
                                'total-tasks' => 'Total Tasks',
                                'milestones-progress' => 'Milestones Progress',
                            ],
                        ],

                        'record-information' => [
                            'title' => 'Record Information',

                            'entries' => [
                                'created-at' => 'Created At',
                                'created-by' => 'Created By',
                                'last-updated' => 'Last Updated',
                            ],
                        ],

                        'settings' => [
                            'title' => 'Project Settings',

                            'entries' => [
                                'visibility' => 'Visibility',
                                'timesheets-enabled' => 'Timesheets Enabled',
                                'milestones-enabled' => 'Milestones Enabled',
                            ],
                        ],
                    ],
                ],

                'pages' => [
                    'create' => [
                        'notification' => [
                            'title' => 'Project created',
                            'body' => 'The project has been created successfully.',
                        ],
                    ],

                    'edit' => [
                        'notification' => [
                            'title' => 'Project updated',
                            'body' => 'The project has been updated successfully.',
                        ],

                        'header-actions' => [
                            'delete' => [
                                'label' => 'New Project',

                                'notification' => [
                                    'title' => 'Project updated',
                                    'body' => 'The project has been updated successfully.',
                                ],
                            ],
                        ],
                    ],

                    'delete' => [
                        'notification' => [
                            'title' => 'Project updated',
                            'body' => 'The project has been updated successfully.',
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                            'my-projects' => 'My Projects',
                            'my-favorite-projects' => 'My Favorites',
                            'unassigned-projects' => 'Unassigned Projects',
                            'archived-projects' => 'Archived Projects',
                        ],

                        'header-actions' => [
                            'create' => [
                                'label' => 'New Project',
                            ],
                        ],
                    ],

                    'view' => [
                        'header-actions' => [
                            'delete' => [
                                'notification' => [
                                    'title' => 'Project deleted',
                                    'body' => 'The project has been deleted successfully.',
                                ],
                            ],
                        ],
                    ],

                    'manage-milestones' => [
                        'title' => 'Milestones',

                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Project Milestone',

                                    'notification' => [
                                        'title' => 'Milestone created',
                                        'body' => 'The milestone has been created successfully.',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'manage-tasks' => [
                        'title' => 'Tasks',

                        'header-actions' => [
                            'create' => [
                                'label' => 'New Task',
                            ],
                        ],

                        'table' => [
                            'actions' => [
                                'restore' => [
                                    'notification' => [
                                        'title' => 'Task restored',
                                        'body' => 'The task has been restored successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Task deleted',
                                        'body' => 'The task has been deleted successfully.',
                                    ],
                                ],
                                
                                'force-delete' => [
                                    'notification' => [
                                        'title' => 'Task force deleted',
                                        'body' => 'The task has been force deleted successfully.',
                                    ],
                                ],
                            ],
                        ],

                        'tabs' => [
                            'open-tasks' => 'Open Tasks',
                            'my-tasks' => 'My Tasks',
                            'unassigned-tasks' => 'Unassigned Tasks',
                            'closed-tasks' => 'Closed Tasks',
                            'starred-tasks' => 'Starred Tasks',
                            'archived-tasks' => 'Archived Tasks',
                        ],
                    ],
                ],

                'relation-managers' => [
                    'milestones' => [
                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Project Milestone',

                                    'notification' => [
                                        'title' => 'Milestone created',
                                        'body' => 'The milestone has been created successfully.',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'task-stages' => [
                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Task Stage',

                                    'notification' => [
                                        'title' => 'Task Stage created',
                                        'body' => 'The task Stage has been created successfully.',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

            'task' => [
                'title' => 'Tasks',

                'navigation' => [
                    'title' => 'Tasks',
                    'group' => 'Project',
                ],

                'global-search' => [
                    'project'   => 'Project',
                    'customer'  => 'Customer',
                    'milestone' => 'Milestone',
                ],

                'form' => [
                    'sections' => [
                        'general' => [
                            'title' => 'General',

                            'fields' => [
                                'title'             => 'Title',
                                'title-placeholder' => 'Task Title...',
                                'tags'              => 'Tags',
                                'name'              => 'Name',
                                'description'       => 'Description',
                                'project'           => 'Project',
                                'status'            => 'Status',
                                'start_date'        => 'Start Date',
                                'end_date'          => 'End Date',
                            ],
                        ],

                        'additional' => [
                            'title' => 'Additional Information',
                        ],

                        'settings' => [
                            'title' => 'Settings',

                            'fields' => [
                                'project'             => 'Project',
                                'milestone'           => 'Milestone',
                                'milestone-hint-text' => 'Deliver your services automatically when a milestone is reached by linking it to a sales order item.',
                                'name'                => 'Name',
                                'deadline'            => 'Deadline',
                                'is-completed'        => 'Is Completed',
                                'customer'            => 'Customer',
                                'assignees'           => 'Assignees',
                                'allocated-hours'     => 'Allocated Hours',
                                'allocated-hours-helper-text' => 'In hours (Eg. 1.5 hours means 1 hour 30 minutes)',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'id'                  => 'ID',
                        'priority'            => 'Priority',
                        'state'               => 'State',
                        'new-state'           => 'New State',
                        'update-state'        => 'Update State',
                        'title'               => 'Title',
                        'project'             => 'Project',
                        'project-placeholder' => 'Private Task',
                        'milestone'           => 'Milestone',
                        'customer'            => 'Customer',
                        'assignees'           => 'Assignees',
                        'allocated-time'      => 'Allocated Time',
                        'time-spent'          => 'Time Spent',
                        'time-remaining'      => 'Time Remaining',
                        'progress'            => 'Progress',
                        'deadline'            => 'Deadline',
                        'tags'                => 'Tags',
                        'stage'               => 'Stage',
                    ],

                    'groups' => [
                        'state'      => 'State',
                        'project'    => 'Project',
                        'milestone'  => 'Milestone',
                        'customer'   => 'Customer',
                        'deadline'   => 'Deadline',
                        'stage'      => 'Stage',
                        'created-at' => 'Created At',
                    ],

                    'filters' => [
                        'title' => 'Title',
                        'priority' => 'Priority',
                        'low' => 'Low',
                        'high' => 'High',
                        'state' => 'State',
                        'tags' => 'Tags',
                        'allocated-hours' => 'Allocated Hours',
                        'total-hours-spent' => 'Total Hours Spent',
                        'remaining-hours' => 'Remaining Hours',
                        'overtime' => 'Overtime',
                        'progress' => 'Progress',
                        'deadline' => 'Deadline',
                        'created-at' => 'Created At',
                        'updated-at' => 'Updated At',
                        'assignees' => 'Assignees',
                        'customer' => 'Customer',
                        'project' => 'Project',
                        'stage' => 'Stage',
                        'milestone' => 'Milestone',
                        'company' => 'Company',
                        'creator' => 'Creator',
                    ],

                    'actions' => [
                        'restore' => [
                            'notification' => [
                                'title' => 'Task restored',
                                'body' => 'The task has been restored successfully.',
                            ],
                        ],

                        'delete' => [
                            'notification' => [
                                'title' => 'Task deleted',
                                'body' => 'The task has been deleted successfully.',
                            ],
                        ],

                        'force-delete' => [
                            'notification' => [
                                'title' => 'Task force deleted',
                                'body' => 'The task has been force deleted successfully.',
                            ],
                        ],
                    ],

                    'bulk-actions' => [
                        'restore' => [
                            'notification' => [
                                'title' => 'Tasks restored',
                                'body' => 'The tasks has been restored successfully.',
                            ],
                        ],

                        'delete' => [
                            'notification' => [
                                'title' => 'Tasks deleted',
                                'body' => 'The tasks has been deleted successfully.',
                            ],
                        ],

                        'force-delete' => [
                            'notification' => [
                                'title' => 'Tasks force deleted',
                                'body' => 'The tasks has been force deleted successfully.',
                            ],
                        ],
                    ]
                ],

                'infolist' => [
                    'sections' => [
                        'general' => [
                            'title' => 'General',

                            'entries' => [
                                'title'       => 'Title',
                                'state'       => 'State',
                                'tags'        => 'Tags',
                                'priority'    => 'Priority',
                                'description' => 'Description',
                            ],
                        ],

                        'project-information' => [
                            'title' => 'Project Information',

                            'entries' => [
                                'project' => 'Project',
                                'milestone' => 'Milestone',
                                'customer' => 'Customer',
                                'assignees' => 'Assignees',
                                'deadline' => 'Deadline',
                                'stage' => 'Stage',
                            ],
                        ],

                        'time-tracking' => [
                            'title' => 'Time Tracking',

                            'entries' => [
                                'allocated-time' => 'Allocated Time',
                                'time-spent' => 'Time Spent',
                                'time-spent-suffix' => ' Hours',
                                'time-remaining' => 'Time Remaining',
                                'time-remaining-suffix' => ' Hours',
                                'progress' => 'Progress',
                            ],
                        ],

                        'additional-information' => [
                            'title' => 'Additional Information',
                        ],

                        'record-information' => [
                            'title' => 'Record Information',

                            'entries' => [
                                'created-at' => 'Created At',
                                'created-by' => 'Created By',
                                'last-updated' => 'Last Updated',
                            ],
                        ],

                        'statistics' => [
                            'title' => 'Statistics',

                            'entries' => [
                                'sub-tasks' => 'Sub Tasks',
                                'timesheet-entries' => 'Timesheet Entries',
                            ],
                        ]
                    ],
                ],

                'pages' => [
                    'create' => [
                        'notification' => [
                            'title' => 'Task created',
                            'body' => 'The task has been created successfully.',
                        ],
                    ],

                    'edit' => [
                        'notification' => [
                            'title' => 'Task updated',
                            'body' => 'The task has been updated successfully.',
                        ],

                        'header-actions' => [
                            'delete' => [
                                'notification' => [
                                    'title' => 'Task deleted',
                                    'body' => 'The task has been deleted successfully.',
                                ],
                            ],
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                            'open-tasks' => 'Open Tasks',
                            'my-tasks' => 'My Tasks',
                            'unassigned-tasks' => 'Unassigned Tasks',
                            'closed-tasks' => 'Closed Tasks',
                            'starred-tasks' => 'Starred Tasks',
                            'archived-tasks' => 'Archived Tasks',
                        ],

                        'header-actions' => [
                            'create' => [
                                'label' => 'New Task',
                            ],
                        ],
                    ],

                    'view' => [
                        'header-actions' => [
                            'delete' => [
                                'notification' => [
                                    'title' => 'Task deleted',
                                    'body' => 'The task has been deleted successfully.',
                                ],
                            ],
                        ],
                    ],

                    'manage-timesheets' => [
                        'title' => 'Sub Tasks',

                        'form' => [
                            'date' => 'Date',
                            'employee' => 'Employee',
                            'description' => 'Description',
                            'time-spent' => 'Time Spent',
                            'time-spent-helper-text' => 'Time spent in hours (Eg. 1.5 hours means 1 hour 30 minutes)',
                        ],

                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Timesheet',

                                    'notification' => [
                                        'title' => 'Timesheet created',
                                        'body' => 'The timesheet has been created successfully.',
                                    ],
                                ],
                            ],

                            'columns' => [
                                'date' => 'Date',
                                'employee' => 'Employee',
                                'description' => 'Description',
                                'time-spent' => 'Time Spent',
                                'time-spent-on-subtasks' => 'Time Spent on Subtasks',
                                'total-time-spent' => 'Total Time Spent',
                                'remaining-time' => 'Remaining Time',
                            ],

                            'actions' => [
                                'edit' => [
                                    'notification' => [
                                        'title' => 'Task updated',
                                        'body' => 'The task has been updated successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Task deleted',
                                        'body' => 'The task has been deleted successfully.',
                                    ],
                                ],
                            ]
                        ],
                    ],

                    'manage-sub-tasks' => [
                        'title' => 'Sub Tasks',

                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Sub Task',

                                    'notification' => [
                                        'title' => 'Task created',
                                        'body' => 'The task has been created successfully.',
                                    ],
                                ],
                            ],

                            'actions' => [
                                'restore' => [
                                    'notification' => [
                                        'title' => 'Task restored',
                                        'body' => 'The task has been restored successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Task deleted',
                                        'body' => 'The task has been deleted successfully.',
                                    ],
                                ],
                                
                                'force-delete' => [
                                    'notification' => [
                                        'title' => 'Task force deleted',
                                        'body' => 'The task has been force deleted successfully.',
                                    ],
                                ],
                            ]
                        ],
                    ],
                ],

                'relation-managers' => [
                    'sub-tasks' => [
                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Sub Task',

                                    'notification' => [
                                        'title' => 'Task created',
                                        'body' => 'The task has been created successfully.',
                                    ],
                                ],
                            ],

                            'actions' => [
                                'restore' => [
                                    'notification' => [
                                        'title' => 'Task restored',
                                        'body' => 'The task has been restored successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Task deleted',
                                        'body' => 'The task has been deleted successfully.',
                                    ],
                                ],
                                
                                'force-delete' => [
                                    'notification' => [
                                        'title' => 'Task force deleted',
                                        'body' => 'The task has been force deleted successfully.',
                                    ],
                                ],
                            ],
                        ],
                    ],

                    'timesheets' => [
                        'form' => [
                            'date' => 'Date',
                            'employee' => 'Employee',
                            'description' => 'Description',
                            'time-spent' => 'Time Spent',
                            'time-spent-helper-text' => 'Time spent in hours (Eg. 1.5 hours means 1 hour 30 minutes)',
                        ],

                        'table' => [
                            'header-actions' => [
                                'create' => [
                                    'label' => 'Add Timesheet',

                                    'notification' => [
                                        'title' => 'Timesheet created',
                                        'body' => 'The timesheet has been created successfully.',
                                    ],
                                ],
                            ],

                            'columns' => [
                                'date' => 'Date',
                                'employee' => 'Employee',
                                'description' => 'Description',
                                'time-spent' => 'Time Spent',
                                'time-spent-on-subtasks' => 'Time Spent on Subtasks',
                                'total-time-spent' => 'Total Time Spent',
                                'remaining-time' => 'Remaining Time',
                            ],

                            'actions' => [
                                'edit' => [
                                    'notification' => [
                                        'title' => 'Task updated',
                                        'body' => 'The task has been updated successfully.',
                                    ],
                                ],

                                'delete' => [
                                    'notification' => [
                                        'title' => 'Task deleted',
                                        'body' => 'The task has been deleted successfully.',
                                    ],
                                ],
                            ]
                        ],
                    ],
                ],
            ],

            'partner' => [
                'form' => [
                    'sections' => [
                        'general' => [
                            'title' => 'General',

                            'fields' => [
                                'company' => 'Company',
                                'avatar' => 'Avatar',
                                'tax-id' => 'Tax ID',
                                'job-title' => 'Job Title',
                                'phone' => 'Phone',
                                'mobile' => 'Mobile',
                                'email' => 'Email',
                                'website' => 'Website',
                                'title' => 'Title',
                                'name' => 'Name',
                                'short-name' => 'Short Name',
                                'tags' => 'Tags',
                            ],
                        ],
                    ],

                    'tabs' => [
                        'sales-purchase' => [
                            'title' => 'Sales and Purchases',

                            'fields' => [
                                'responsible' => 'Responsible',
                                'responsible-hint-text' => 'This is internal salesperson responsible for this customer',
                                'company-id' => 'Company ID',
                                'company-id-hint-text' => 'The registry number of the company. Use it if it is different from the Tax ID. It must be unique across all partners of a same country',
                                'reference' => 'Reference',
                                'industry' => 'Industry',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
