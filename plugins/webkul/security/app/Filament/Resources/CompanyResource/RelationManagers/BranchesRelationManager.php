<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Security\Models\Currency;

class BranchesRelationManager extends RelationManager
{
    protected static string $relationship = 'branches';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Branch Tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General Information')
                            ->schema([
                                Forms\Components\Section::make('Branch Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Company Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),
                                        Forms\Components\TextInput::make('registration_number')
                                            ->label('Registration Number'),
                                        Forms\Components\TextInput::make('tax_id')
                                            ->label('Tax ID')
                                            ->unique(ignoreRecord: true)
                                            ->hintAction(
                                                Action::make('help')
                                                    ->icon('heroicon-o-question-mark-circle')
                                                    ->extraAttributes(['class' => 'text-gray-500'])
                                                    ->hiddenLabel()
                                                    ->tooltip('The Tax ID is a unique identifier for your company.')
                                            ),
                                        Forms\Components\ColorPicker::make('color')
                                            ->label('Color'),
                                    ])
                                    ->columns(2),
                                Forms\Components\Section::make('Branding')
                                    ->schema([
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Company Logo')
                                            ->image()
                                            ->directory('company-logos')
                                            ->visibility('private'),
                                    ]),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Tabs\Tab::make('Address Information')
                            ->schema([
                                Forms\Components\Section::make('Contact Information')
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
                                            ->options(fn () => Currency::pluck('name', 'code'))
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
                                    ]),
                            ])
                            ->columnSpanFull(),
                        Forms\Components\Tabs\Tab::make('Contact Information')
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
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpanFull(),
                    ]),
            ])
            ->columns('full');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Branch Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->sortable()
                    ->label('Email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->sortable()
                    ->label('City')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->sortable()
                    ->label('Country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('currency_code')
                    ->sortable()
                    ->label('Currency')
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
                    ->date()
                    ->collapsible(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
