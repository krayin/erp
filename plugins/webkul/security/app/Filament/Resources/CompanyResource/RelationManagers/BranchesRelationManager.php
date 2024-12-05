<?php

namespace Webkul\Security\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

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
                                            ->label('Branch Name')
                                            ->required()
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('branch_id')
                                            ->label('Branch ID')
                                            ->required()
                                            ->unique(),
                                        Forms\Components\TextInput::make('tax_id')
                                            ->label('Tax ID')
                                            ->required()
                                            ->unique(),
                                        Forms\Components\ColorPicker::make('color')
                                            ->label('Branch Color'),
                                        Forms\Components\FileUpload::make('logo')
                                            ->label('Branch Logo')
                                            ->image()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make('Address Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('street1')
                                            ->label('Street Address 1')
                                            ->required(),
                                        Forms\Components\TextInput::make('street2')
                                            ->label('Street Address 2'),
                                        Forms\Components\TextInput::make('city')
                                            ->label('City')
                                            ->required(),
                                        Forms\Components\TextInput::make('state')
                                            ->label('State')
                                            ->required(),
                                        Forms\Components\TextInput::make('zip')
                                            ->label('ZIP Code')
                                            ->required(),
                                        Forms\Components\TextInput::make('country')
                                            ->label('Country')
                                            ->required(),
                                    ])
                                    ->columns(2),
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
                                Forms\Components\Section::make('Additional Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('currency')
                                            ->label('Default Currency')
                                            ->required(),
                                    ]),
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
