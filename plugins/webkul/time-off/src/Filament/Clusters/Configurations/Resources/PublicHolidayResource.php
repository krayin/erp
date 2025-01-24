<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources;

use Webkul\TimeOff\Filament\Clusters\Configurations;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\PublicHolidayResource\Pages;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Infolists\Infolist;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Models\CalendarLeaves;

class PublicHolidayResource extends Resource
{
    protected static ?string $model = CalendarLeaves::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Configurations::class;

    protected static ?string $modelLabel = 'Public Holiday';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Hidden::make('time_type')
                                ->default('leave'),
                            Forms\Components\TextInput::make('name')
                                ->label('Name')
                                ->required()
                                ->placeholder('Enter the name of the public holiday'),
                        ])->columns(2),

                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\DatePicker::make('date_from')
                                ->label('Date From')
                                ->native(false)
                                ->required(),
                            Forms\Components\DatePicker::make('date_to')
                                ->label('Date To')
                                ->required()
                                ->native(false),
                        ])->columns(2),
                    Forms\Components\Select::make('calendar')
                        ->searchable()
                        ->preload()
                        ->relationship('calendar', 'name'),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Name'),
                Tables\Columns\TextColumn::make('date_from')
                    ->sortable()
                    ->label('Date From'),
                Tables\Columns\TextColumn::make('date_to')
                    ->sortable()
                    ->label('Date To'),
                Tables\Columns\TextColumn::make('calendar.name')
                    ->sortable()
                    ->label('Calendar'),
            ])
            ->groups([
                Tables\Grouping\Group::make('date_from')
                    ->label(__('Date From'))
                    ->collapsible(),
                Tables\Grouping\Group::make('date_to')
                    ->label(__('Date To'))
                    ->collapsible(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\ColorEntry::make('name')
                    ->placeholder('—')
                    ->label(__('Name')),
                Infolists\Components\TextEntry::make('date_from')
                    ->placeholder('-')
                    ->icon('heroicon-o-calendar')
                    ->label(__('Date From')),
                Infolists\Components\TextEntry::make('date_to')
                    ->date()
                    ->placeholder('-')
                    ->icon('heroicon-o-calendar')
                    ->label(__('Date To')),
                Infolists\Components\TextEntry::make('calendar.name')
                    ->placeholder('-')
                    ->icon('heroicon-o-clock')
                    ->label(__('Working Hours')),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublicHolidays::route('/'),
        ];
    }
}
