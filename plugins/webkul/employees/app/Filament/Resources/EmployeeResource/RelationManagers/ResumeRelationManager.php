<?php

namespace Webkul\Employee\Filament\Resources\EmployeeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Employee\Enums;
use Webkul\Employee\Models\EmployeeResumeLineType;

class ResumeRelationManager extends RelationManager
{
    protected static string $relationship = 'resumes';

    protected static ?string $title = 'Resumes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make([
                    Forms\Components\TextInput::make('name')
                        ->label('Title')
                        ->required()
                        ->reactive(),
                    Forms\Components\Select::make('type')
                        ->label('Type')
                        ->relationship(name: 'resumeType', titleAttribute: 'name')
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Name')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true),
                                    Forms\Components\Hidden::make('creator_id')
                                        ->default(Auth::user()->id)
                                        ->required(),
                                    Forms\Components\Hidden::make('sort')
                                        ->default(EmployeeResumeLineType::max('sort') + 1)
                                        ->required(),
                                ])->columns(2),
                        ])
                        ->createOptionAction(function (Action $action) {
                            return $action
                                ->modalHeading('Create Type')
                                ->modalSubmitActionLabel('Create Type')
                                ->modalWidth('2xl');
                        }),
                    Forms\Components\Fieldset::make('Duration')
                        ->schema([
                            Forms\Components\DatePicker::make('start_date')
                                ->label('Start Date')
                                ->required()
                                ->native(false)
                                ->reactive(),
                            Forms\Components\Datepicker::make('end_date')
                                ->label('End Date')
                                ->native(false)
                                ->reactive(),
                        ]),
                    Forms\Components\Select::make('display_type')
                        ->preload()
                        ->options(Enums\ResumeDisplayType::options())
                        ->label('Display Type')
                        ->searchable()
                        ->required()
                        ->reactive(),
                    Forms\Components\Textarea::make('description')
                        ->label('Description'),
                ])->columns(2),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Group::make()
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Title')
                                    ->icon('heroicon-o-document-text'),
                                Infolists\Components\TextEntry::make('display_type')
                                    ->label('Display Type')
                                    ->icon('heroicon-o-document'),
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('resumeType.name')
                                            ->label('Type'),
                                    ]),
                                Infolists\Components\TextEntry::make('description')
                                    ->label('Description'),
                            ])->columns(2),
                        Infolists\Components\Fieldset::make('Duration')
                            ->schema([
                                Infolists\Components\TextEntry::make('start_date')
                                    ->label('Start Date')
                                    ->icon('heroicon-o-calendar'),
                                Infolists\Components\TextEntry::make('end_date')
                                    ->label('End Date')
                                    ->icon('heroicon-o-calendar'),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan('full'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable()
                    ->toggleable()
                    ->date(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable()
                    ->toggleable()
                    ->date(),
                Tables\Columns\TextColumn::make('display_type')
                    ->label('Display Type')
                    ->default(fn ($record) => Enums\ResumeDisplayType::options()[$record->display_type])
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('creator.name')
                    ->label('Creator')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->sortable()
                    ->toggleable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->sortable()
                    ->toggleable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('type.name')
                    ->label('Grouped by Type')
                    ->collapsible(),

                Tables\Grouping\Group::make('display_type')
                    ->label('Grouped by Display Type')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type_id')
                    ->label('Type')
                    ->relationship('resumeType', 'name')
                    ->searchable(),
                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('start')
                            ->label('Start Date From'),
                        Forms\Components\DatePicker::make('end')
                            ->label('Start Date To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['start'],
                                fn ($query, $start) => $query->whereDate('start_date', '>=', $start)
                            )
                            ->when(
                                $data['end'],
                                fn ($query, $end) => $query->whereDate('start_date', '<=', $end)
                            );
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Created From'),
                        Forms\Components\DatePicker::make('to')
                            ->label('Created To'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn ($query, $from) => $query->whereDate('created_at', '>=', $from)
                            )
                            ->when(
                                $data['to'],
                                fn ($query, $to) => $query->whereDate('created_at', '<=', $to)
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Resume')
                    ->modalHeading('Create Resume')
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::user()->id;
                        $data['user_id'] = Auth::user()->id;

                        return $data;
                    }),
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
}
