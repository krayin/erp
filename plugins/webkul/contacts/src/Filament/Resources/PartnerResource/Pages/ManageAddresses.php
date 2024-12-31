<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Resources\PartnerResource;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Partner\Enums\AddressType;
use Webkul\Contact\Filament\Resources\AddressResource;

class ManageAddresses extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'addresses';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    public static function getNavigationLabel(): string
    {
        return __('contacts::filament/resources/partner/pages/manage-addresses.title');
    }

    public function form(Form $form): Form
    {
        return AddressResource::form($form);
    }

    public function table(Table $table): Table
    {
        return AddressResource::table($table);
    }
}
