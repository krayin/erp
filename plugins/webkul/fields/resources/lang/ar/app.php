<?php

return [
    'navigation' => [
        'label' => 'الحقول المخصصة',
        'group' => 'الإعدادات',
    ],

    'model-label' => 'الحقول',

    'resources' => [
        'pages' => [
            'list-records' => [
                'index' => [
                    'title' => 'الحقول المخصصة',
                ],
            ],
        ],
    ],

    'form' => [
        'sections' => [
            'main'              => 'تفاصيل الحقل',
            'options'           => 'الخيارات',
            'form-settings'     => 'إعدادات النموذج',
            'table-settings'    => 'إعدادات الجدول',
            'infolist-settings' => 'إعدادات قائمة المعلومات',
            'settings'          => 'الإعدادات',
            'resource'          => 'المورد',
        ],

        'fields' => [
            'name'              => 'الاسم',
            'code'              => 'الرمز',
            'type'              => 'النوع',
            'field-input-types' => 'نوع الإدخال',
            'sort-order'        => 'ترتيب الفرز',
            'resource'          => 'المورد',
            'is-multiselect'    => 'السماح بتحديد متعدد',

            'types' => [
                'text'          => 'حقل نصي',
                'textarea'      => 'منطقة نصية',
                'select'        => 'قائمة منسدلة',
                'checkbox'      => 'مربع اختيار',
                'radio'         => 'زر اختيار',
                'toggle'        => 'تبديل',
                'checkbox-list' => 'قائمة مربعات اختيار',
                'datetime'      => 'منتقي التاريخ والوقت',
                'editor'        => 'محرر نصوص منسقة',
                'markdown'      => 'محرر ماركداون',
                'color'         => 'منتقي الألوان',
            ],

            'input-types' => [
                'text'     => 'نص',
                'email'    => 'بريد إلكتروني',
                'numeric'  => 'رقمي',
                'integer'  => 'عدد صحيح',
                'password' => 'كلمة المرور',
                'tel'      => 'هاتف',
                'url'      => 'رابط',
                'color'    => 'لون',
            ],
        ],

        'actions' => [
            'add-option' => 'إضافة خيار',
        ],
    ],

    'table' => [
        'columns' => [
            'code'       => 'الرمز',
            'name'       => 'الاسم',
            'type'       => 'النوع',
            'resource'   => 'المورد',
            'created-at' => 'تاريخ الإنشاء',
        ],

        'filters' => [
            'type' => [
                'label' => 'النوع',
                'types' => [
                    'text'          => 'حقل نصي',
                    'textarea'      => 'منطقة نصية',
                    'select'        => 'قائمة منسدلة',
                    'checkbox'      => 'مربع اختيار',
                    'radio'         => 'زر اختيار',
                    'toggle'        => 'تبديل',
                    'checkbox-list' => 'قائمة مربعات اختيار',
                    'datetime'      => 'منتقي التاريخ والوقت',
                    'editor'        => 'محرر نصوص منسقة',
                    'markdown'      => 'محرر ماركداون',
                    'color'         => 'منتقي الألوان',
                ],
            ],
            'resource' => [
                'label' => 'المورد',
            ],
        ],
    ],
];
