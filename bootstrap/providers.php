<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    Webkul\Analytic\AnalyticServiceProvider::class,
    Webkul\Chatter\ChatterServiceProvider::class,
    Webkul\Support\SupportServiceProvider::class,
    Webkul\Field\FieldServiceProvider::class,
    Webkul\Partner\PartnerServiceProvider::class,
    Webkul\Project\ProjectServiceProvider::class,
    Webkul\TableViews\TableViewsServiceProvider::class,
    Webkul\Security\SecurityServiceProvider::class,
    Webkul\Employee\EmployeeServiceProvider::class,
];
