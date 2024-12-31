<?php

namespace Webkul\Contact\Filament\Resources\PartnerResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Resources\PartnerResource;

class ManageContacts extends ManageRelatedRecords
{
    protected static string $resource = PartnerResource::class;

    protected static string $relationship = 'contacts';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationLabel(): string
    {
        return __('contacts::filament/resources/partner/pages/manage-contacts.title');
    }

    public function form(Form $form): Form
    {
        return PartnerResource::form($form);
    }

    public function table(Table $table): Table
    {
        return PartnerResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('contacts::filament/resources/partner/pages/manage-contacts.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner/pages/manage-contacts.table.header-actions.create.notification.title'))
                            ->body(__('contacts::filament/resources/partner/pages/manage-contacts.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
