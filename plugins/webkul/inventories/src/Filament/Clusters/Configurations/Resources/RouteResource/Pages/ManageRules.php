<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource\Pages;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RuleResource;

class ManageRules extends ManageRelatedRecords
{
    protected static string $resource = RouteResource::class;

    protected static string $relationship = 'rules';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function getNavigationLabel(): string
    {
        return __('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.title');
    }

    public function form(Form $form): Form
    {
        return RuleResource::form($form);
    }

    public function table(Table $table): Table
    {
        return RuleResource::table($table)
            ->columns([
                Tables\Columns\TextColumn::make('action')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sourceLocation.full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('destinationLocation.full_name')
                    ->searchable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label(__('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.table.header-actions.create.label'))
                    ->icon('heroicon-o-plus-circle')
                    ->fillForm(function (array $arguments): array {
                        return [
                            'route_id' => $this->getOwnerRecord()->id,
                        ];
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        $data['company_id'] = $data['company_id'] ?? Auth::user()->default_company_id;

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/route/pages/manage-rules.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
