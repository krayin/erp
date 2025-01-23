<?php

namespace Webkul\Inventory\Filament\Clusters\Configurations\Resources\RouteResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Inventory\Filament\Clusters\Configurations\Resources\RuleResource;

class RulesRelationManager extends RelationManager
{
    protected static string $relationship = 'rules';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('inventories::filament/clusters/configurations/resources/route/relation-managers/rules.title');
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
                    ->label(__('inventories::filament/clusters/configurations/resources/route/relation-managers/rules.table.header-actions.create.label'))
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
                            ->title(__('inventories::filament/clusters/configurations/resources/route/relation-managers/rules.table.header-actions.create.notification.title'))
                            ->body(__('inventories::filament/clusters/configurations/resources/route/relation-managers/rules.table.header-actions.create.notification.body')),
                    ),
            ]);
    }
}
