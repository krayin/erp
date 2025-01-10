<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\EmploymentTypeResource\Pages;
use Webkul\Employee\Models\EmploymentType;

class EmploymentTypeResource extends Resource
{
    protected static ?string $model = EmploymentType::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    public static function getModelLabel(): string
    {
        return __('employees::filament/clusters/configurations/resources/employment-type.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('employees::filament/clusters/configurations/resources/employment-type.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('employees::filament/clusters/configurations/resources/employment-type.navigation.title');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'country_id',
            'creator_id',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('employees::filament/clusters/configurations/resources/employment-type.global-search.name')       => $record->name ?? '—',
            __('employees::filament/clusters/configurations/resources/employment-type.global-search.country')    => $record->country?->name ?? '—',
            __('employees::filament/clusters/configurations/resources/employment-type.global-search.created-by') => $record->createdBy?->name ?? '—',
        ];
    }

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('creator_id')
                    ->default(Auth::user()->id),
                Forms\Components\TextInput::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.form.fields.name'))
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true),
                Forms\Components\TextInput::make('code')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.form.fields.name')),
                Forms\Components\Select::make('country_id')
                    ->searchable()
                    ->preload()
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.form.fields.country'))
                    ->relationship('country', 'name'),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.id'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable()
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.name')),
                Tables\Columns\TextColumn::make('code')
                    ->sortable()
                    ->searchable()
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.code')),
                Tables\Columns\TextColumn::make('country.name')
                    ->sortable()
                    ->searchable()
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.country')),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.created-by'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.filters.name'))
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('country')
                            ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.filters.country'))
                            ->icon('heroicon-o-map')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('createdBy')
                            ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.filters.created-by'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.filters.updated-at')),
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('code')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.groups.code'))
                    ->collapsible(),
                Tables\Grouping\Group::make('country.name')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.groups.country'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.groups.created-at'))
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['sort'] = EmploymentType::max('sort') + 1;

                        $data['code'] = $data['code'] ?? $data['name'];

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('employees::filament/clusters/configurations/resources/employment-type.table.actions.edit.notification.title'))
                            ->body(__('employees::filament/clusters/configurations/resources/employment-type.table.actions.edit.notification.body'))
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('employees::filament/clusters/configurations/resources/employment-type.table.actions.delete.notification.title'))
                            ->body(__('employees::filament/clusters/configurations/resources/employment-type.table.actions.delete.notification.body'))
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('employees::filament/clusters/configurations/resources/employment-type.table.bulk-actions.delete.notification.title'))
                                ->body(__('employees::filament/clusters/configurations/resources/employment-type.table.bulk-actions.delete.notification.body'))
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('employees::filament/clusters/configurations/resources/employment-type.table.empty-state-actions.create.notification.title'))
                            ->body(__('employees::filament/clusters/configurations/resources/employment-type.table.empty-state-actions.create.notification.body'))
                    )
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->reorderable('sort')
            ->defaultSort('sort', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('name')
                    ->icon('heroicon-o-user')
                    ->placeholder('—')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.infolist.entries.name')),
                Infolists\Components\TextEntry::make('code')
                    ->placeholder('—')
                    ->icon('heroicon-o-user')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.infolist.entries.code')),
                Infolists\Components\TextEntry::make('country.name')
                    ->placeholder('—')
                    ->icon('heroicon-o-map')
                    ->label(__('employees::filament/clusters/configurations/resources/employment-type.infolist.entries.country')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmploymentTypes::route('/'),
        ];
    }
}
