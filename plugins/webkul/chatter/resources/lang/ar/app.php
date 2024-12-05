<?php

return [
    'filament' => [
        'actions' => [
            'chatter' => [
                'activity' => [
                    'form' => [
                        'activity-type'          => 'نوع النشاط',
                        'due-date'               => 'تاريخ الاستحقاق',
                        'summary'                => 'الملخص',
                        'assigned-to'            => 'مُسند إلى',
                        'type-your-message-here' => 'اكتب رسالتك هنا...',
                    ],

                    'action' => [
                        'label'               => 'جدولة نشاط',
                        'modal-submit-action' => [
                            'title' => 'جدولة',
                        ],
                        'notification' => [
                            'success' => [
                                'title' => 'تم جدولة النشاط',
                                'body'  => 'تمت جدولة نشاطك بنجاح.',
                            ],
                            'danger' => [
                                'title' => 'فشل جدولة النشاط',
                                'body'  => 'حدث خطأ أثناء جدولة نشاطك.',
                            ],
                        ],
                    ],
                ],

                'file' => [
                    'form' => [
                        'file' => 'ملف',
                    ],

                    'action' => [
                        'label'               => 'إضافة ملفات',
                        'modal-submit-action' => [
                            'title' => 'إضافة ملفات',
                        ],
                        'notification' => [
                            'success' => [
                                'title' => 'تم إرسال الملف',
                                'body'  => 'تم إرسال ملفك بنجاح.',
                            ],
                            'danger' => [
                                'title' => 'فشل إرسال الملف',
                                'body'  => 'حدث خطأ أثناء إرسال رسالتك.',
                            ],
                        ],
                    ],
                ],

                'follower' => [
                    'modal' => [
                        'heading' => 'المتابعون',
                    ],
                ],

                'log' => [
                    'label'               => 'تسجيل ملاحظة',
                    'modal-submit-action' => [
                        'log' => 'تسجيل',
                    ],
                    'form' => [
                        'type-your-message-here' => 'اكتب رسالتك هنا...',
                    ],

                    'notification' => [
                        'success' => [
                            'title' => 'تمت إضافة السجل',
                            'body'  => 'تمت إضافة ملاحظة السجل الخاصة بك بنجاح.',
                        ],
                        'danger' => [
                            'title' => 'لم يتم إضافة السجل',
                            'body'  => 'حدث خطأ أثناء إضافة ملاحظة السجل الخاصة بك.',
                        ],
                    ],
                ],

                'message' => [
                    'form' => [
                        'type-your-message-here' => 'اكتب رسالتك هنا...',
                    ],
                    'label'               => 'إرسال رسالة',
                    'modal-submit-action' => [
                        'title' => 'إرسال',
                    ],

                    'notification' => [
                        'success' => [
                            'title' => 'تم إرسال الرسالة',
                            'body'  => 'تم إرسال رسالتك بنجاح.',
                        ],
                        'danger' => [
                            'title' => 'فشل إرسال الرسالة',
                            'body'  => 'حدث خطأ أثناء إرسال رسالتك.',
                        ],
                    ],
                ],

                'action' => [
                    'modal' => [
                        'label'       => 'الدردشة',
                        'description' => 'إضافة رسائل، ملاحظات، أنشطة، مرفقات ملفات، والمزيد.',
                    ],
                ],
            ],
        ],

        'resources' => [
            'task' => [
                'label' => 'المهام',

                'pages' => [
                    'list' => [
                        'tabs' => [
                            'my-tasks'      => 'مهامي',
                            'pending-tasks' => 'المهام المعلقة',
                        ],
                    ],
                ],

                'form' => [
                    'section' => [
                        'task-details' => [
                            'title'       => 'تفاصيل المهمة',
                            'description' => 'قدم عنوانًا ووصفًا للمهمة',
                            'schema'      => [
                                'title'       => 'عنوان المهمة',
                                'description' => 'وصف المهمة',
                            ],
                        ],

                        'task-status' => [
                            'title'       => 'حالة المهمة',
                            'description' => 'حدد حالة وتاريخ استحقاق المهمة',
                            'schema'      => [
                                'status'   => 'حالة المهمة',
                                'due-date' => 'تاريخ الاستحقاق',
                            ],
                        ],

                        'task-assignment' => [
                            'title'       => 'تعيين المهمة',
                            'description' => 'إدارة إنشاء وتعيين المهمة',
                            'schema'      => [
                                'created-by'  => 'تم الإنشاء بواسطة',
                                'assigned-to' => 'مُسند إلى',
                                'followers'   => 'المتابعون',
                            ],
                        ],

                        'additional-information' => [
                            'title'       => 'معلومات إضافية',
                            'description' => 'قدم معلومات إضافية عن المهمة',
                            'schema'      => [
                                'priority' => 'الأولوية',
                                'tags'     => 'العلامات',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'title'           => 'العنوان',
                        'status'          => 'الحالة',
                        'due-date'        => 'تاريخ الاستحقاق',
                        'created-by'      => 'تم الإنشاء بواسطة',
                        'assigned-to'     => 'مُسند إلى',
                        'created-at'      => 'تم الإنشاء في',
                        'updated-at'      => 'تم التحديث في',
                        'followers-count' => 'عدد المتابعين',
                    ],

                    'filters' => [
                        'status'      => 'الحالة',
                        'created-by'  => 'تم الإنشاء بواسطة',
                        'assigned-to' => 'مُسند إلى',
                    ],
                ],

                'infolist' => [
                    'section' => [
                        'task-details' => [
                            'title'       => 'تفاصيل المهمة',
                            'description' => 'عرض عنوان ووصف المهمة',
                            'schema'      => [
                                'title'       => 'عنوان المهمة',
                                'description' => 'وصف المهمة',
                            ],
                        ],
                        'task-status' => [
                            'title'       => 'حالة المهمة',
                            'description' => 'تحديد حالة وتاريخ استحقاق المهمة',
                            'schema'      => [
                                'status'   => 'حالة المهمة',
                                'due_date' => 'تاريخ الاستحقاق',
                            ],
                        ],
                        'task-assignment' => [
                            'title'       => 'تعيين المهمة',
                            'description' => 'إدارة إنشاء وتعيين المهمة',
                            'schema'      => [
                                'created_by'  => 'تم الإنشاء بواسطة',
                                'assigned_to' => 'مُسند إلى',
                                'followers'   => 'المتابعون',
                            ],
                        ],
                        'additional-information' => [
                            'title'       => 'معلومات إضافية',
                            'description' => 'هذه هي معلومات الحقول المخصصة',
                        ],
                    ],
                ],

                'navigation' => [
                    'title' => 'المهام',
                ],
            ],
        ],
    ],

    'livewire' => [
        'chatter_panel' => [
            'actions' => [
                'follower' => [
                    'add_success'    => 'تمت إضافة المتابع بنجاح.',
                    'remove_success' => 'تمت إزالة المتابع بنجاح.',
                    'error'          => 'خطأ في إدارة المتابع.',
                ],
                'delete_chat' => [
                    'confirmation' => 'هل أنت متأكد من رغبتك في حذف هذه الدردشة؟',
                ],
            ],
            'placeholders' => [
                'no_record_found' => 'لم يتم العثور على سجلات.',
                'loading'         => 'جارٍ تحميل المحادثة...',
            ],
            'notifications' => [
                'success' => 'نجاح',
                'error'   => 'خطأ',
            ],
            'search' => [
                'placeholder' => 'البحث عن المستخدمين بالاسم أو البريد الإلكتروني',
            ],
        ],

        'follower' => [
            'actions' => [
                'toggle' => [
                    'add_success'    => 'تمت إضافة :name كمتابع بنجاح.',
                    'remove_success' => 'تمت إزالة :name من المتابعين بنجاح.',
                    'error'          => 'خطأ في إدارة المتابع',
                ],
            ],
        ],
    ],

    'trait' => [
        'activity-log-failed' => [
            'events' => [
                'created'      => 'تم إنشاء :model جديد',
                'updated'      => 'تم تحديث :model',
                'deleted'      => 'تم حذف :model',
                'soft-deleted' => 'تم الحذف المؤقت لـ :model',
                'hard-deleted' => 'تم الحذف الدائم لـ :model',
                'restored'     => 'تمت استعادة :model',
            ],
            'attributes' => [
                'unassigned' => 'غير مُسند',
            ],
            'errors' => [
                'user-fetch-failed'   => 'فشل جلب المستخدم للحقل :field',
                'activity-log-failed' => 'فشل إنشاء سجل النشاط: :message',
            ],
        ],
    ],

    'views' => [
        'filament' => [
            'infolists' => [
                'components' => [
                    'content-text-entry' => [
                        'attachments'      => 'المرفقات',
                        'activity-details' => 'تفاصيل النشاط',
                        'created-by'       => 'تم الإنشاء بواسطة',
                        'summary'          => 'الملخص',
                        'due-date'         => 'تاريخ الاستحقاق',
                        'assigned-to'      => 'مُسند إلى',
                        'changes-made'     => 'التغييرات المُجراة',
                        'modified'         => 'تم تعديل <b>:field</b>',
                    ],

                    'title-text-entry' => [
                        'tooltip' => [
                            'delete' => 'حذف التعليق',
                        ],
                    ],
                ],
            ],
        ],

        'livewire' => [
            'current-followers'       => 'المتابعون الحاليون',
            'no-followers-yet'        => 'لا يوجد متابعون حتى الآن.',
            'add-followers'           => 'إضافة متابعين',
            'add'                     => 'إضافة',
            'adding'                  => 'جارٍ الإضافة...',
            'user-not-found-matching' => 'لم يتم العثور على مستخدم يطابق ":query"',
        ],
    ],
];
