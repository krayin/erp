<?php

namespace Webkul\Invoice\Filament\Clusters\Configuration\Resources\PaymentTermResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Webkul\Invoice\Traits\PaymentDueTerm;

class PaymentDueTermRelationManager extends RelationManager
{
    use PaymentDueTerm;

    protected static string $relationship = 'dueTerm';

    protected static ?string $title = 'Due Terms';
}
