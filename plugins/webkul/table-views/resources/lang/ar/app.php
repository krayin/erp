<?php

return [
    'filament' => [
        'actions' => [
            'create-view' => [
                'form' => [
                    'name'                  => 'الاسم',
                    'color'                 => 'اللون',
                    'icon'                  => 'الأيقونة',
                    'add-to-favorites'      => 'إضافة إلى المفضلة',
                    'add-to-favorites-help' => 'أضف هذا المرشح إلى المفضلة',
                    'make-public'           => 'جعله عامًا',
                    'make-public-help'      => 'جعل هذا المرشح متاحًا لجميع المستخدمين',
                    'options'               => [
                        'danger'  => 'خطر',
                        'gray'    => 'رمادي',
                        'info'    => 'معلومات',
                        'success' => 'نجاح',
                        'warning' => 'تحذير',
                    ],

                    'notification' => [
                        'created' => 'تم إنشاء العرض بنجاح',
                    ],

                    'modal' => [
                        'title' => 'حفظ العرض',
                    ],
                ],
            ],

            'edit-view' => [
                'form' => [
                    'name'                  => 'الاسم',
                    'color'                 => 'اللون',
                    'icon'                  => 'الأيقونة',
                    'add-to-favorites'      => 'إضافة إلى المفضلة',
                    'add-to-favorites-help' => 'أضف هذا المرشح إلى المفضلة',
                    'make-public'           => 'جعله عامًا',
                    'make-public-help'      => 'جعل هذا المرشح متاحًا لجميع المستخدمين',
                    'options'               => [
                        'danger'  => 'خطر',
                        'gray'    => 'رمادي',
                        'info'    => 'معلومات',
                        'success' => 'نجاح',
                        'warning' => 'تحذير',
                    ],

                    'notification' => [
                        'created' => 'تم إنشاء العرض بنجاح',
                    ],

                    'modal' => [
                        'title' => 'تعديل العرض',
                    ],
                ],
            ],
        ],

        'traits' => [
            'has-table-views' => [
                'title'                 => 'العروض',
                'default'               => 'الافتراضي',
                'apply-view'            => 'تطبيق العرض',
                'add-to-favorites'      => 'إضافة إلى المفضلة',
                'remove-from-favorites' => 'إزالة من المفضلة',
                'delete-view'           => 'حذف العرض',
                'replace-view'          => 'استبدال العرض',
            ],
        ],
    ],

    'views' => [
        'component' => [
            'tables' => [
                'table-views' => [
                    'title'           => 'العروض',
                    'reset'           => 'إعادة تعيين',
                    'favorites-views' => 'العروض المفضلة',
                    'saved-views'     => 'العروض المحفوظة',
                    'preset-views'    => 'العروض المعدة مسبقًا',
                ],
            ],
        ],
    ],
];
