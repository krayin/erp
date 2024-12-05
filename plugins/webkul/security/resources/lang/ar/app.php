<?php

return [
    'filament' => [
        'clusters' => [
            'pages' => [
                'manage-users' => [
                    'title'                              => 'إدارة المستخدمين',
                    'enable-user-invitation'             => 'تمكين دعوة المستخدم',
                    'enable-user-invitation-helper-text' => 'السماح للمسؤولين بدعوة المستخدمين عبر البريد الإلكتروني مع تعيين الأدوار والصلاحيات.',
                    'enable-reset-password'              => 'تمكين إعادة تعيين كلمة المرور',
                    'enable-reset-password-helper-text'  => 'السماح للمستخدمين بإعادة تعيين كلمات المرور من صفحة تسجيل الدخول.',
                    'default-role'                       => 'الدور الافتراضي',
                    'default-role-helper-text'           => 'الدور الذي يتم تعيينه للمستخدمين عند التسجيل عبر الدعوة.',
                ],
            ],

            'settings' => [
                'name'  => 'الإعدادات',
                'group' => 'الإعدادات',
            ],
        ],

        'resources' => [
            'user' => [
                'title' => 'المستخدمون',

                'pages' => [
                    'create' => [
                        'created-notification-title' => 'تم تسجيل المستخدم',
                    ],

                    'edit' => [
                        'header-actions' => [
                            'action' => [
                                'title' => 'تم الحفظ بنجاح',
                            ],
                            'form' => [
                                'new-password'         => 'كلمة مرور جديدة',
                                'confirm-new-password' => 'تأكيد كلمة المرور الجديدة',
                            ],
                        ],
                    ],

                    'list' => [
                        'tabs' => [
                            'all'      => 'جميع المستخدمين',
                            'archived' => 'المؤرشف',
                        ],

                        'header-actions' => [
                            'invite-user' => [
                                'title' => 'دعوة مستخدم',
                                'modal' => [
                                    'title'               => 'دعوة مستخدم',
                                    'submit-action-label' => 'دعوة مستخدم',
                                ],
                                'notification' => [
                                    'title' => 'تمت دعوة المستخدم بنجاح!',
                                ],
                            ],

                            'form' => [
                                'email' => 'البريد الإلكتروني',
                            ],
                        ],
                    ],
                ],

                'navigation' => [
                    'title' => 'المستخدمون',
                    'group' => 'الإعدادات',
                ],

                'form' => [
                    'sections' => [
                        'general' => [
                            'title'  => 'عام',
                            'fields' => [
                                'name'                  => 'الاسم',
                                'email'                 => 'البريد الإلكتروني',
                                'password'              => 'كلمة المرور',
                                'password-confirmation' => 'تأكيد كلمة المرور الجديدة',
                            ],
                        ],

                        'permissions' => [
                            'title'  => 'الصلاحيات',
                            'fields' => [
                                'roles'               => 'الأدوار',
                                'resource-permission' => 'صلاحيات المورد',
                                'teams'               => 'الفرق',
                            ],
                        ],
                    ],
                ],

                'table' => [
                    'columns' => [
                        'name'                => 'الاسم',
                        'email'               => 'البريد الإلكتروني',
                        'teams'               => 'الفرق',
                        'role'                => 'الدور',
                        'resource-permission' => 'صلاحيات المورد',
                        'created-at'          => 'تاريخ الإنشاء',
                        'updated-at'          => 'تاريخ التحديث',
                    ],

                    'filters' => [
                        'resource-permission' => 'صلاحيات المورد',
                        'teams'               => 'الفرق',
                        'roles'               => 'الأدوار',
                    ],
                ],
            ],

            'team' => [
                'title' => 'الفرق',

                'navigation' => [
                    'title' => 'الفرق',
                    'group' => 'الإعدادات',
                ],

                'form' => [
                    'name' => 'الاسم',
                ],

                'table' => [
                    'name' => 'الاسم',
                ],
            ],

            'role' => [
                'navigation' => [
                    'title' => 'الأدوار',
                    'group' => 'الإعدادات',
                ],
            ],
        ],
    ],

    'livewire' => [
        'header' => [
            'sub-heading' => [
                'accept-invitation' => 'أنشئ مستخدمك لقبول الدعوة',
            ],
        ],
    ],

    'mail' => [
        'user-invitation' => [
            'subject' => 'دعوة للانضمام إلى :app',
            'accept'  => [
                'button' => 'قبول الدعوة',
            ],
        ],
    ],
];
