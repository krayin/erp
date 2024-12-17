<?php

namespace Webkul\Employee\Filament\Clusters\Configurations\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Configurations;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\ActivityTypeResource\Pages;
use Webkul\Employee\Models\Department;
use Webkul\Employee\Models\Employee;
use Webkul\Security\Models\User;
use Webkul\Support\Enums\ActivityChainingType;
use Webkul\Support\Enums\ActivityDecorationType;
use Webkul\Support\Enums\ActivityDelayFrom;
use Webkul\Support\Enums\ActivityDelayUnit;
use Webkul\Support\Enums\ActivityTypeAction;
use Webkul\Support\Models\ActivityType;

class ActivityTypeResource extends Resource
{
    protected static ?string $model = ActivityType::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $cluster = Configurations::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Activity Type Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Activity Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->hintIcon('heroicon-o-question-mark-circle', tooltip: 'Enter the official activity type name'),
                                        Forms\Components\Select::make('category')
                                            ->label('Action')
                                            ->options(ActivityTypeAction::options())
                                            ->live()
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('default_user_id')
                                            ->label('Default User')
                                            ->options(fn () => User::query()->pluck('name', 'id'))
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('model_type')
                                            ->label('Model')
                                            ->options([
                                                Employee::class   => 'Employee',
                                                Department::class => 'Department',
                                            ])
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Textarea::make('summary')
                                            ->label('Summary')
                                            ->columnSpanFull(),
                                        Forms\Components\RichEditor::make('default_note')
                                            ->label('Note')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                                Forms\Components\Section::make('Delay Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('delay_count')
                                            ->label('Delay Count')
                                            ->numeric()
                                            ->minValue(0),
                                        Forms\Components\Select::make('delay_unit')
                                            ->label('Delay Unit')
                                            ->options(ActivityDelayUnit::options()),
                                        Forms\Components\Select::make('delay_from')
                                            ->label('Delay From')
                                            ->options(ActivityDelayFrom::options())
                                            ->helperText('Source of delay calculation'),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Advanced Settings')
                                    ->schema([
                                        \Guava\FilamentIconPicker\Forms\IconPicker::make('icon')
                                            ->label('Icon')
                                            ->sets(['heroicons', 'fontawesome-solid'])
                                            ->columns(4)
                                            ->preload()
                                            ->optionsLimit(50),
                                        Forms\Components\Select::make('decoration_type')
                                            ->label('Decoration Type')
                                            ->options(ActivityDecorationType::options())
                                            ->native(false),
                                        Forms\Components\Select::make('chaining_type')
                                            ->label('Chaining Type')
                                            ->options(ActivityChainingType::options())
                                            ->default('suggest')
                                            ->live()
                                            ->native(false)
                                            ->hidden(fn (Get $get) => $get('category') === 'upload_file'),
                                        Forms\Components\Select::make('activity_type_suggestions')
                                            ->multiple()
                                            ->relationship('suggestedActivityTypes', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->label('Suggest')
                                            ->hidden(fn (Get $get) => $get('chaining_type') === 'trigger' || $get('category') === 'upload_file'),
                                        Forms\Components\Select::make('triggered_next_type_id')
                                            ->relationship('activityTypes', 'name')
                                            ->label('Trigger')
                                            ->hidden(fn (Get $get) => $get('chaining_type') === 'suggest' && $get('category') !== 'upload_file'),
                                    ]),
                                Forms\Components\Section::make('Status and Configuration')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_active')
                                            ->label('Status')
                                            ->default(false),
                                        Forms\Components\Toggle::make('keep_done')
                                            ->label('Keep Done Activities')
                                            ->default(false),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('summary')
                    ->label('Summary')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delay_count')
                    ->label('Planned in')
                    ->formatStateUsing(function ($record) {
                        return $record->delay_count ? "{$record->delay_count} {$record->delay_unit}" : 'No Delay';
                    }),
                Tables\Columns\TextColumn::make('delay_from')
                    ->label('Type')
                    ->formatStateUsing(fn ($state) => ActivityDelayFrom::options()[$state])
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->label('Action')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Tables\Grouping\Group::make('name')
                    ->label('Activity Name')
                    ->collapsible(),
                Tables\Grouping\Group::make('category')
                    ->label('Action Category')
                    ->collapsible(),
                Tables\Grouping\Group::make('is_active')
                    ->label('Active Status')
                    ->collapsible(),
                Tables\Grouping\Group::make('delay_count')
                    ->label('Delay Count')
                    ->collapsible(),
                Tables\Grouping\Group::make('delay_unit')
                    ->label('Delay Unit')
                    ->collapsible(),
                Tables\Grouping\Group::make('delay_from')
                    ->label('Delay Source')
                    ->collapsible(),
                Tables\Grouping\Group::make('model_type')
                    ->label('Associated Model')
                    ->collapsible(),
                Tables\Grouping\Group::make('chaining_type')
                    ->label('Chaining Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('decoration_type')
                    ->label('Decoration Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('defaultUser.name')
                    ->label('Default User')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Creation Date')
                    ->date()
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Last Updated')
                    ->date()
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->multiple()
                    ->options(ActivityTypeAction::options()),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\Filter::make('has_delay')
                    ->label('Has Delay')
                    ->query(fn ($query) => $query->whereNotNull('delay_count')),
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
                ]),
            ])
            ->reorderable('sort');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListActivityTypes::route('/'),
            'create' => Pages\CreateActivityType::route('/create'),
            'view'   => Pages\ViewActivityType::route('/{record}'),
            'edit'   => Pages\EditActivityType::route('/{record}/edit'),
        ];
    }
}
