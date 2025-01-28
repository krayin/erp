<?php

namespace Webkul\Sale\Filament\Clusters\Configuration\Resources;

use Webkul\Sale\Filament\Clusters\Configuration;
use Webkul\Sale\Filament\Clusters\Configuration\Resources\QuotationTemplateResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Webkul\Sale\Models\OrderTemplate;

class QuotationTemplateResource extends Resource
{
    protected static ?string $model = OrderTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Configuration::class;

    public static function getModelLabel(): string
    {
        return __('Quotation Template');
    }

    public static function getNavigationLabel(): string
    {
        return __('Quotation Templates');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('Sales Orders');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'company.name',
            'name',
        ];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            __('Company') => $record->company?->name ?? '—',
            __('name')    => $record->name ?? '—',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListQuotationTemplates::route('/'),
            'create' => Pages\CreateQuotationTemplate::route('/create'),
            'view'   => Pages\ViewQuotationTemplate::route('/{record}'),
            'edit'   => Pages\EditQuotationTemplate::route('/{record}/edit'),
        ];
    }
}
