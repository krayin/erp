<?php

return [
    'navigation' => [
        'label' => 'الحقول المخصصة',
        'group' => 'الإعدادات',
    ],

    'model-label' => 'الحقول',

    'actions' => [
        'create-view' => [
            'form' => [
                'name'                  => 'الاسم',
                'color'                 => 'اللون',
                'icon'                  => 'الأيقونة',
                'add-to-favorites'      => 'إضافة إلى المفضلة',
                'add-to-favorites-help' => 'أضف هذا الفلتر إلى المفضلة',
                'make-public'           => 'جعلها عامة',
                'make-public-help'      => 'اجعل هذا الفلتر متاحًا لجميع المستخدمين',
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
                'add-to-favorites-help' => 'أضف هذا الفلتر إلى المفضلة',
                'make-public'           => 'جعلها عامة',
                'make-public-help'      => 'اجعل هذا الفلتر متاحًا لجميع المستخدمين',
                'options'               => [
                    'danger'  => 'خطر',
                    'gray'    => 'رمادي',
                    'info'    => 'معلومات',
                    'success' => 'نجاح',
                    'warning' => 'تحذير',
                ],

                'notification' => [
                    'created' => 'تم تعديل العرض بنجاح',
                ],

                'modal' => [
                    'title' => 'تعديل العرض',
                ],
            ],
        ],
    ],

    'views' => [
        'default'                 => 'افتراضي',
        'title'                   => 'العروض',
        'apply-view'              => 'تطبيق العرض',
        'add-to-favorites'        => 'إضافة إلى المفضلة',
        'remove-from-favorites'   => 'إزالة من المفضلة',
        'delete-view'             => 'حذف العرض',
        'replace-view'            => 'استبدال العرض',
        'reset'            => 'إعادة تعيين',
'favorites-views'  => 'العروض المفضلة',
'saved-views'      => 'العروض المحفوظة',
'preset-views'     => 'العروض المسبقة',
    ],
];
