<?php

return [
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
];