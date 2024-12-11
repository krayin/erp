<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    Webkul\Chatter\ChatterServiceProvider::class,
    Webkul\Support\SupportServiceProvider::class,
    Webkul\Fields\FieldsServiceProvider::class,
    Webkul\TableViews\TableViewsServiceProvider::class,
    Webkul\Security\SecurityServiceProvider::class,
    Webkul\Employee\EmployeeServiceProvider::class,
];
