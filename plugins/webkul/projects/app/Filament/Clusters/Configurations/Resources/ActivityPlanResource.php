<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Project\Filament\Clusters\Configurations;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;
use Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\RelationManagers;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Support\Models\ActivityPlan;
use Webkul\Project\Models\Project;

class ActivityPlanResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = ActivityPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?int $navigationSort = 5;

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Plan Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Status')
                        ->default(true)
                        ->inline(false),
                    Forms\Components\Section::make('Additional Information')
                        ->visible(! empty($customFormFields = static::getCustomFormFields()))
                        ->description('Additional information about this work schedule')
                        ->schema($customFormFields)
                        ->columns(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters(static::mergeCustomTableFilters([]))
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ])
            ->modifyQueryUsing(function ($query) {
                $query->where('model_type', Project::class);
            });
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ActivityTemplateRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListActivityPlans::route('/'),
            'view'   => Pages\ViewActivityPlan::route('/{record}'),
            'edit'   => Pages\EditActivityPlan::route('/{record}/edit'),
        ];
    }
}
