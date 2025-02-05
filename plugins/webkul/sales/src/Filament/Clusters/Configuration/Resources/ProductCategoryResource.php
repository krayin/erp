<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sale\Filament\Clusters\Configuration;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductCategoryResource\Pages;
use Webkul\Sale\Models\ProductCategory;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('sales::filament/clusters/configurations/resources/product-category.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('sales::filament/clusters/configurations/resources/product-category.navigation.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('sales::filament/clusters/configurations/resources/product-category.navigation.group');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('sales::filament/clusters/configurations/resources/product-category.global-search.name') => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.form.sections.fields.name'))
                            ->maxLength(255),
                        Forms\Components\Select::make('parent_id')
                            ->relationship('parent', 'full_name')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.form.sections.fields.parent-category'))
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.columns.complete-name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->numeric()
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.columns.created-by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.columns.created-at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.columns.updated-at'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.name'))
                            ->icon('heroicon-o-tag'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('full_name')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.complete-name'))
                            ->icon('heroicon-o-tag'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('parent_id')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.parent-category'))
                            ->icon('heroicon-o-folder')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('full_name')
                                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.parent-category'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator_id')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.created-by'))
                            ->icon('heroicon-o-user')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.created-by'))
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('created_at')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.created-at')),
                        Tables\Filters\QueryBuilder\Constraints\DateConstraint::make('updated_at')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.table.filters.updated-at')),
                    ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.groups.name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('full_name')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.groups.complete-name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('parent.full_name')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.groups.parent-complete-name'))
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.groups.created-by'))
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.groups.created-at'))
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label(__('sales::filament/clusters/configurations/resources/product-category.table.groups.updated-at'))
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('sales::filament/clusters/configurations/resources/product-category.table.actions.delete.notification.title'))
                            ->body(__('sales::filament/clusters/configurations/resources/product-category.table.actions.delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('sales::filament/clusters/configurations/resources/product-category.table.bulk-actions.delete.notification.title'))
                                ->body(__('sales::filament/clusters/configurations/resources/product-category.table.bulk-actions.delete.notification.body')),
                        ),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->icon('heroicon-o-tag')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.infolist.sections.entries.name')),
                        Infolists\Components\TextEntry::make('full_name')
                            ->icon('heroicon-o-folder')
                            ->label(__('sales::filament/clusters/configurations/resources/product-category.infolist.sections.entries.parent-category'))
                    ])->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'view'   => Pages\ViewProductCategory::route('/{record}'),
            'edit'   => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
