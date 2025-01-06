<?php

namespace Webkul\Support\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Webkul\Support\Filament\Resources\EmailTemplateResource\Pages;
use Webkul\Support\Models\EmailTemplate;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Template Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('subject')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->label('Subject'),
                                        Forms\Components\RichEditor::make('content')
                                            ->required()
                                            ->columnSpanFull()
                                            ->label('Content'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('General Information')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->label('Name'),
                                                Forms\Components\Textarea::make('description')
                                                    ->rows(3)
                                                    ->required()
                                                    ->unique(ignoreRecord: true)
                                                    ->label('Description'),
                                            ])->columns(1),
                                    ]),

                                Forms\Components\Section::make('Sender Configuration')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('sender_name')
                                                    ->label('Sender Name')
                                                    ->helperText('Leave empty to use default system sender name')
                                                    ->nullable(),
                                                Forms\Components\TextInput::make('sender_email')
                                                    ->label('Sender Email')
                                                    ->email()
                                                    ->helperText('Leave empty to use default system sender email')
                                                    ->nullable(),
                                            ])->columns(1),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public static function table(Tables\Table $table): Tables\Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Status'))
                    ->sortable()
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make(__('Template Information'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('subject')
                                            ->label(__('Subject'))
                                            ->icon('heroicon-o-document-text')
                                            ->placeholder('—'),
                                        Infolists\Components\TextEntry::make('content')
                                            ->html()
                                            ->icon('heroicon-o-document-text')
                                            ->label(__('Content'))
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpan(2),

                        // Right Section: General and Sender Information
                        Infolists\Components\Group::make()
                            ->schema([
                                // General Information Section
                                Infolists\Components\Section::make(__('General Information'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('name')
                                            ->label(__('Name'))
                                            ->icon('heroicon-o-user-circle')
                                            ->placeholder('—'),
                                        Infolists\Components\TextEntry::make('description')
                                            ->label(__('Description'))
                                            ->icon('heroicon-o-information-circle')
                                            ->placeholder('—')
                                            ->columnSpanFull(),
                                    ]),

                                // Sender Configuration Section
                                Infolists\Components\Section::make(__('Sender Configuration'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('sender_name')
                                            ->label(__('Sender Name'))
                                            ->icon('heroicon-o-user')
                                            ->placeholder('—')
                                            ->helperText(__('Leave empty to use the default system sender name')),
                                        Infolists\Components\TextEntry::make('sender_email')
                                            ->label(__('Sender Email'))
                                            ->icon('heroicon-o-mail')
                                            ->placeholder('—')
                                            ->helperText(__('Leave empty to use the default system sender email')),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit'   => Pages\EditEmailTemplate::route('/{record}/edit'),
            'view'   => Pages\ViewEmailTemplate::route('/{record}'),
        ];
    }
}
