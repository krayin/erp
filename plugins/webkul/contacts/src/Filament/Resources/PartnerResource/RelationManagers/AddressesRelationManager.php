<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Webkul\Contact\Filament\Resources\AddressResource;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return AddressResource::form($form);
    }

    public function table(Table $table): Table
    {
        return AddressResource::table($table);
    }
}
