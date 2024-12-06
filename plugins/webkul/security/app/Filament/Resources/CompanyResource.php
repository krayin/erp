<?php

namespace Webkul\Security\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Security\Filament\Resources\CompanyResource\Pages;
use Webkul\Security\Filament\Resources\CompanyResource\RelationManagers;
use Webkul\Security\Models\Company;

class CompanyResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function getNavigationGroup(): string
    {
        return 'Settings';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Company Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Company Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),
                                        Forms\Components\TextInput::make('company_id')
                                            ->label('Company ID')
                                            ->required()
                                            ->unique()
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('The Company ID is a unique identifier for your company.')
                                            ),
                                        Forms\Components\TextInput::make('tax_id')
                                            ->label('Tax ID')
                                            ->required()
                                            ->unique()
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('The Tax ID is a unique identifier for your company.')
                                            ),
                                        Forms\Components\ColorPicker::make('color')
                                            ->label('Company Color'),

                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Company Logo')
                                            ->image()
                                            ->columnSpan(['lg' => 2]),
                                    ])
                                    ->columns(2),
                                Forms\Components\Section::make('Address Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('street1')
                                            ->label('Street 1')
                                            ->required(),
                                        Forms\Components\TextInput::make('street2')
                                            ->label('Street 2'),
                                        Forms\Components\TextInput::make('city')
                                            ->required(),
                                        Forms\Components\TextInput::make('state')
                                            ->required(),
                                        Forms\Components\TextInput::make('zip')
                                            ->label('ZIP Code')
                                            ->required(),
                                        Forms\Components\TextInput::make('country')
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Contact Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('phone')
                                            ->label('Phone Number')
                                            ->required(),
                                        Forms\Components\TextInput::make('mobile')
                                            ->label('Mobile Number'),
                                        Forms\Components\TextInput::make('email')
                                            ->label('Email Address')
                                            ->required()
                                            ->email(),
                                    ]),
                                Forms\Components\Section::make('Additional Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('currency')
                                            ->label('Default Currency')
                                            ->required(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('city')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('country')->sortable(),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('currency'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('city')
                    ->label('City')
                    ->collapsible(),
                Tables\Grouping\Group::make('country')
                    ->label('Country')
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('email')
                    ->label('Email')
                    ->collapsible(),
                Tables\Grouping\Group::make('phone')
                    ->label('Phone')
                    ->collapsible(),
                Tables\Grouping\Group::make('currency')
                    ->label('Currency')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BranchesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCompanies::route('/'),
            'create' => Pages\CreateCompany::route('/create'),
            'view'   => Pages\ViewCompany::route('/{record}'),
            'edit'   => Pages\EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
