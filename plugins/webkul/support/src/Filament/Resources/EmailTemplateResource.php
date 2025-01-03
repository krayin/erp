<?php

namespace Webkul\Support\Filament\Resources;

use Webkul\Support\Filament\Resources\EmailTemplateResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Webkul\Support\Models\EmailTemplate;
use Webkul\Support\Services\EmailTemplateService;

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
                Tables\Columns\TextColumn::make('is_active'),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
            ])->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
