<?php

namespace Webkul\Employee\Filament\Clusters\Employee\Resources;

use Webkul\Employee\Filament\Clusters\Employee;
use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\Pages;
use Webkul\Employee\Filament\Clusters\Employee\Resources\SkillTypeResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Webkul\Employee\Models\Skill;
use Webkul\Employee\Models\SkillType;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;
use Webkul\Employee\Models\SkillLevel;

class SkillTypeResource extends Resource
{
    protected static ?string $model = SkillType::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Management';

    protected static ?int $navigationSort = 1;

    protected static ?string $cluster = Employee::class;

    // public static function form(Form $form): Form
    // {
    //     return $form
    //         ->schema([
    //             Section::make('Skill Type Details')
    //                 ->description('Create and manage skill type.')
    //                 ->schema([
    //                     Grid::make(2)
    //                         ->schema([
    // Forms\Components\TextInput::make('name')
    //     ->label('Skill Type')
    //     ->required()
    //     ->unique(ignoreRecord: true)
    //     ->maxLength(255)
    //     ->placeholder('Enter skill type name'),

    //                         ]),
    //                 ])
    //                 ->columns(1)
    //         ]);
    // }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Skill Type Details')
                                    ->description('Create and manage skill type.')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Skill Type')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->placeholder('Enter skill type name'),
                                        Forms\Components\ColorPicker::make('color')
                                            ->label('color')
                                            ->required(),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Skills and Levels')
                                    ->schema([
                                        Forms\Components\Select::make('skills')
                                            ->label('Skills')
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->preload()
                                            ->options(fn() => Skill::pluck('name', 'id'))
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                            ])
                                            ->createOptionAction(function (Action $action) {
                                                return $action
                                                    ->modalHeading('Create Skill')
                                                    ->modalSubmitActionLabel('Create Skill')
                                                    ->modalWidth('lg')
                                                    ->action(function (array $data, $component) {
                                                        $skill = Skill::create([
                                                            'code' => $data['code'],
                                                            'name' => $data['name'],
                                                        ]);

                                                        $component->state($skill->code);

                                                        Notification::make()
                                                            ->title('Currency Created Successfully')
                                                            ->success()
                                                            ->send();
                                                    });
                                            }),
                                        Forms\Components\Select::make('levels')
                                            ->label('Levels')
                                            ->searchable()
                                            ->required()
                                            ->live()
                                            ->preload()
                                            ->options(fn() => SkillLevel::pluck('name', 'id'))
                                            ->createOptionForm([
                                                Forms\Components\TextInput::make('name')
                                                    ->label('Name')
                                                    ->required(),
                                            ])
                                            ->createOptionAction(function (Action $action) {
                                                return $action
                                                    ->modalHeading('Create Level')
                                                    ->modalSubmitActionLabel('Create Level')
                                                    ->modalWidth('lg')
                                                    ->action(function (array $data, $component) {
                                                        $level = SkillLevel::create([
                                                            'code' => $data['code'],
                                                            'name' => $data['name'],
                                                        ]);

                                                        $component->state($level->code);

                                                        Notification::make()
                                                            ->title('Currency Created Successfully')
                                                            ->success()
                                                            ->send();
                                                    });
                                            }),
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
                Tables\Columns\TextColumn::make('name')
                    ->label('Skill Type Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'technical' => 'primary',
                        'soft_skills' => 'success',
                        'language' => 'warning',
                        'professional' => 'info',
                        default => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'technical' => 'Technical',
                        'soft_skills' => 'Soft Skills',
                        'language' => 'Language',
                        'professional' => 'Professional',
                        'other' => 'Other'
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSkillTypes::route('/'),
            'create' => Pages\CreateSkillType::route('/create'),
            'view' => Pages\ViewSkillType::route('/{record}'),
            'edit' => Pages\EditSkillType::route('/{record}/edit'),
        ];
    }
}
