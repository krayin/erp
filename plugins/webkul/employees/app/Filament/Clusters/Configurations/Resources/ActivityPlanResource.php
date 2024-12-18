<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\Pages;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityPlanResource\RelationManagers;
use Webkul\Employee\Filament\Resources\DepartmentResource;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Support\Models\ActivityPlan;

class ActivityPlanResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = ActivityPlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

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
                        Forms\Components\Hidden::make('creator_id')
                            ->default(Auth::user()->id),
                        Forms\Components\Hidden::make('plugin')
                            ->default('employees'),
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->relationship(name: 'department', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm(fn (Form $form) => DepartmentResource::form($form))
                            ->editOptionForm(fn (Form $form) => DepartmentResource::form($form)),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status')
                            ->default(true)
                            ->inline(false),
                        Forms\Components\Section::make('Additional Information')
                            ->visible(! empty($customFormFields = static::getCustomFormFields()))
                            ->description('Additional information about this work schedule')
                            ->schema($customFormFields)
                            ->columns(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('plugin')
                    ->label('Related Plugin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->numeric()
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->badge()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->sortable()
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters(static::mergeCustomTableFilters([]))
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('createdBy.name')
                    ->label('Created By')
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label('Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle'),
            ]);
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
