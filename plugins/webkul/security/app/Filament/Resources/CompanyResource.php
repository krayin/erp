<?php

namespace Webkul\Security\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Webkul\Fields\Filament\Traits\HasCustomFields;
use Webkul\Security\Filament\Resources\CompanyResource\Pages;
use Webkul\Security\Filament\Resources\CompanyResource\RelationManagers;
use Webkul\Security\Models\Company;
use Webkul\Security\Models\Currency;

class CompanyResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function getNavigationGroup(): string
    {
        return __('Settings');
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
                                        Forms\Components\TextInput::make('registration_number')
                                            ->label('Registration Number'),
                                        Forms\Components\TextInput::make('company_id')
                                            ->label('Company ID')
                                            ->unique(ignoreRecord: true)
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
                                            ->unique(ignoreRecord: true)
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('The Tax ID is a unique identifier for your company.')
                                            ),
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
                                Forms\Components\Section::make('Additional Information')
                                    ->schema([
                                        Forms\Components\Select::make('currency_code')
                                            ->label('Default Currency')
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->preload()
                                            ->options(fn() => Currency::pluck('name', 'code'))
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('code')
                                                    ->label('Currency Code')
                                                    ->required()
                                                    ->unique('currencies', 'code', ignoreRecord: true),
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Currency Name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->unique('currencies', 'name', ignoreRecord: true),
                                            ])
                                            ->createOptionAction(function (Action $action) {
                                                return $action
                                                    ->modalHeading('Create Currency')
                                                    ->modalSubmitActionLabel('Create Currency')
                                                    ->modalWidth('lg')
                                                    ->action(function (array $data, $component) {
                                                        $currency = Currency::create([
                                                            'code' => $data['code'],
                                                            'name' => $data['name'],
                                                        ]);

                                                        $component->state($currency->code);

                                                        Notification::make()
                                                            ->title('Currency Created Successfully')
                                                            ->success()
                                                            ->send();
                                                    });
                                            }),
                                        Forms\Components\DatePicker::make('founded_date')
                                            ->native(false)
                                            ->label('Company Founding Date'),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Active Status')
                                            ->default(true),
                                        ...static::getCustomFormFields(),
                                    ])->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Branding')
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Company Logo')
                                            ->image()
                                            ->directory('company-logos')
                                            ->visibility('private'),
                                        Forms\Components\ColorPicker::make('color')
                                            ->label('Color'),
                                    ]),
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
                Tables\Columns\ImageColumn::make('logo')
                    ->size(50)
                    ->label('Logo'),
                Tables\Columns\TextColumn::make('name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branches.name')
                    ->label('Branches')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->label('City')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->label('Country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_code')
                    ->label('Currency')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                Tables\Grouping\Group::make('currency_code')
                    ->label('Currency')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('Status')
                    ->options([
                        true  => 'Active',
                        false => 'Inactive',
                    ]),
                Tables\Filters\SelectFilter::make('country')
                    ->label('Country')
                    ->multiple()
                    ->options(function () {
                        return Company::distinct('country')->pluck('country', 'country');
                    }),
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
            ])->modifyQueryUsing(function (Builder $query) {
                $query->where('user_id', Auth::user()->id);
            });
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
