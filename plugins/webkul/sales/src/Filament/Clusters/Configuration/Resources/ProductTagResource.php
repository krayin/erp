<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Webkul\Sale\Filament\Clusters\Configuration;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\ProductTagResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Product\Models\Tag;

class ProductTagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Tags');
    }

    public static function getNavigationLabel(): string
    {
        return __('Tags');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Products');
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
            __('Name') => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->placeholder(__('Name')),
                Forms\Components\ColorPicker::make('color')
                    ->label(__('Color'))
                    ->required()
                    ->placeholder(__('Color')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(__('Name')),
                Tables\Columns\ColorColumn::make('color')
                    ->label(__('Color')),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('Created By')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductTags::route('/'),
        ];
    }
}
