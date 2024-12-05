<?php

return [
    'filament' => [
        'actions' => [
            'chatter' => [
                'activity' => [
                    'form' => [
                        'activity-type'          => 'Activity Type',
                        'due-date'               => 'Due Date',
                        'summary'                => 'Summary',
                        'assigned-to'            => 'Assigned To',
                        'type-your-message-here' => 'Type your message here...',
                    ],

                    'action' => [
                        'label'               => 'Schedule Activity',
                        'modal-submit-action' => [
                            'title' => 'Schedule',
                        ],
                        'notification' => [
                            'success' => [
                                'title' => 'Activity Scheduled',
                                'body'  => 'Your activity has been scheduled successfully.',
                            ],
                            'danger' => [
                                'title' => 'Activity Scheduling Failed',
                                'body'  => 'An error occurred while scheduling your activity.',
                            ],
                        ],
                    ],
                ],

                'file' => [
                    'form' => [
                        'file' => 'File',
                    ],

                    'action' => [
                        'label'               => 'Add Files',
                        'modal-submit-action' => [
                            'title' => 'Add Files',
                        ],
                        'notification' => [
                            'success' => [
                                'title' => 'File Sent',
                                'body'  => 'Your file has been sent successfully.',
                            ],
                            'danger' => [
                                'title' => 'File Sending Failed',
                                'body'  => 'An error occurred while sending your message.',
                            ],
                        ],
                    ],
                ],

                'follower' => [
                    'modal' => [
                        'heading' => 'Followers',
                    ],
                ],

                'log' => [
                    'label'               => 'Log Note',
                    'modal-submit-action' => [
                        'log' => 'Log',
                    ],
                    'form' => [
                        'type-your-message-here' => 'Type your message here...',
                    ],

                    'notification' => [
                        'success' => [
                            'title' => 'Log Added',
                            'body'  => 'Your log note has been added successfully.',
                        ],
                        'danger' => [
                            'title' => 'Log Not Added',
                            'body'  => 'An error occurred while adding your log note.',
                        ],
                    ],
                ],

                'message' => [
                    'form' => [
                        'type-your-message-here' => 'Type your message here...',
                    ],
                    'label'               => 'Send Message',
                    'modal-submit-action' => [
                        'title' => 'Send',
                    ],

                    'notification' => [
                        'success' => [
                            'title' => 'Message Sent',
                            'body'  => 'Your message has been sent successfully.',
                        ],
                        'danger' => [
                            'title' => 'Message Sending Failed',
                            'body'  => 'An error occurred while sending your message.',
                        ],
                    ],
                ],

                'action' => [
                    'modal' => [
                        'label'       => 'Chatter',
                        'description' => 'Add messages, notes, activities, file attachments, and more.',
                    ],
                ],
            ],
        ],

        'resources' => [
            'task' => [
                'label' => 'Tasks',

                'pages' => [
                    'list' => [
                        'tabs' => [
                            'my-tasks'      => 'My Tasks',
                            'pending-tasks' => 'Pending Tasks',
                        ],
                    ],
                ],

                'form' => [
                    'section' => [
                        'task-details' => [
                            'title'       => 'Task Details',
                            'description' => 'Provide a title and description for the task',
                            'schema'      => [
                                'title'       => 'Task Title',
                                'description' => 'Task Description',
                            ],
                        ],

                        'task-status' => [
                            'title'       => 'Task Status',
                            'description' => 'Specify the status and due date of the task',
                            'schema'      => [
                                'status'   => 'Task Status',
                                'due-date' => 'Due Date',
                            ],
                        ],

                        'task-assignment' => [
                            'title'       => 'Task Assignment',
                            'description' => 'Manage task creation and assignment',
                            'schema'      => [
                                'created-by'  => 'Created By',
                                'assigned-to' => 'Assigned To',
                                'followers'   => 'Followers',
                            ],
                        ],

                        'additional-information' => [
                            'title'       => 'Additional Information',
                            'description' => 'Provide additional information about the task',
                            'schema'      => [
                                'priority' => 'Priority',
                                'tags'     => 'Tags',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'title'           => 'Title',
                        'status'          => 'Status',
                        'due-date'        => 'Due Date',
                        'created-by'      => 'Created By',
                        'assigned-to'     => 'Assigned To',
                        'created-at'      => 'Created At',
                        'updated-at'      => 'Updated At',
                        'followers-count' => 'Followers Count',
                    ],

                    'filters' => [
                        'status'      => 'Status',
                        'created-by'  => 'Created By',
                        'assigned-to' => 'Assigned To',
                    ],
                ],

                'infolist' => [
                    'section' => [
                        'task-details' => [
                            'title'       => 'Task Details',
                            'description' => 'View the title and description of the task',
                            'schema'      => [
                                'title'       => 'Task Title',
                                'description' => 'Task Description',
                            ],
                        ],
                        'task-status' => [
                            'title'       => 'Task Status',
                            'description' => 'Specify the status and due date of the task',
                            'schema'      => [
                                'status'   => 'Task Status',
                                'due_date' => 'Due Date',
                            ],
                        ],
                        'task-assignment' => [
                            'title'       => 'Task Assignment',
                            'description' => 'Manage task creation and assignment',
                            'schema'      => [
                                'created_by'  => 'Created By',
                                'assigned_to' => 'Assigned To',
                                'followers'   => 'Followers',
                            ],
                        ],
                        'additional-information' => [
                            'title'       => 'Additional Information',
                            'description' => 'This is the custom fields information',
                        ],
                    ],
                ],

                'navigation' => [
                    'title' => 'Tasks',
                ],
            ],
        ],
    ],

    'livewire' => [
        'chatter_panel' => [
            'actions' => [
                'follower' => [
                    'add_success'    => 'Follower added successfully.',
                    'remove_success' => 'Follower removed successfully.',
                    'error'          => 'Error managing follower.',
                ],
                'delete_chat' => [
                    'confirmation' => 'Are you sure you want to delete this chat?',
                ],
            ],
            'placeholders' => [
                'no_record_found' => 'No record found.',
                'loading'         => 'Loading Chatter...',
            ],
            'notifications' => [
                'success' => 'Success',
                'error'   => 'Error',
            ],
            'search' => [
                'placeholder' => 'Search users by name or email',
            ],
        ],

        'follower' => [
            'actions' => [
                'toggle' => [
                    'add_success'    => 'Successfully added :name as a follower.',
                    'remove_success' => 'Successfully removed :name as a follower.',
                    'error'          => 'Error managing follower',
                ],
            ],
        ],
    ],

    'trait' => [
        'activity-log-failed' => [
            'events' => [
                'created'      => 'A new :model was created',
                'updated'      => 'The :model was updated',
                'deleted'      => 'The :model was deleted',
                'soft-deleted' => 'The :model was soft deleted',
                'hard-deleted' => 'The :model was permanently deleted',
                'restored'     => 'The :model was restored',
            ],
            'attributes' => [
                'unassigned' => 'Unassigned',
            ],
            'errors' => [
                'user-fetch-failed'   => 'Failed to fetch user for field :field',
                'activity-log-failed' => 'Activity Log Creation Failed: :message',
            ],
        ],
    ],

    'views' => [
        'filament' => [
            'infolists' => [
                'components' => [
                    'content-text-entry' => [
                        'attachments'      => 'Attachments',
                        'activity-details' => 'Activity Details',
                        'created-by'       => 'Created By',
                        'summary'          => 'Summary',
                        'due-date'         => 'Due date',
                        'assigned-to'      => 'Assigned To',
                        'changes-made'     => 'Changes Made',
                        'modified'         => 'The <b>:field</b> has been',
                    ],

                    'title-text-entry' => [
                        'tooltip' => [
                            'delete' => 'Delete Comment',
                        ],
                    ],
                ],
            ],
        ],

        'livewire' => [
            'current-followers'       => 'Current Followers',
            'no-followers-yet'        => 'No followers yet.',
            'add-followers'           => 'Add Followers',
            'add'                     => 'Add',
            'adding'                  => 'Adding...',
            'user-not-found-matching' => 'No user found matching ":query"',
        ],
    ],
];
