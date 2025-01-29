<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource\Pages;

use Filament\Resources\Pages\ManageRelatedRecords;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource;
use Webkul\Invoice\Traits\PaymentDueTerm;

class ManagePaymentDueTerm extends ManageRelatedRecords
{
    use PaymentDueTerm;

    protected static string $resource = PaymentTermResource::class;

    protected static string $relationship = 'dueTerm';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function getNavigationLabel(): string
    {
        return __('Manage Due Terms');
    }
}
