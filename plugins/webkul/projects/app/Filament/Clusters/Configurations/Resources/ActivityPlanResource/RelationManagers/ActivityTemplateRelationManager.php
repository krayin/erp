<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Resources\ActivityPlanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Security\Models\User;
use Webkul\Support\Enums\ActivityDelayInterval;
use Webkul\Support\Enums\ActivityDelayUnit;
use Webkul\Support\Enums\ActivityResponsibleType;
use Webkul\Support\Filament\Resources\ActivityTypeResource;
use Webkul\Support\Models\ActivityPlanTemplate;
use Webkul\Support\Models\ActivityType;

class ActivityTemplateRelationManager extends RelationManager
{
    protected static string $relationship = 'activityPlanTemplates';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Activity Details')
                                    ->schema([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\Select::make('activity_type_id')
                                                    ->options(ActivityType::pluck('name', 'id'))
                                                    ->relationship('activityType', 'name')
                                                    ->searchable()
                                                    ->createOptionForm(fn (Form $form) => ActivityTypeResource::form($form))
                                                    ->preload()
                                                    ->label('Activity type')
                                                    ->live()
                                                    ->afterStateUpdated(function ($state, callable $set) {
                                                        $activityType = ActivityType::find($state);

                                                        if ($activityType && $activityType->default_user_id) {
                                                            $set('responsible_type', ActivityResponsibleType::OTHER->value);

                                                            $set('responsible_id', $activityType->default_user_id);
                                                        }
                                                    }),
                                                Forms\Components\TextInput::make('summary')
                                                    ->label('Summary'),
                                            ])->columns(2),
                                        Forms\Components\RichEditor::make('note')
                                            ->label('Note'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 2]),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Assignment')
                                    ->schema([
                                        Forms\Components\Select::make('responsible_type')
                                            ->label('Assignment')
                                            ->options(ActivityResponsibleType::options())
                                            ->searchable()
                                            ->live()
                                            ->preload(),
                                        Forms\Components\Select::make('responsible_id')
                                            ->label('Assignee')
                                            ->options(fn () => User::pluck('name', 'id'))
                                            ->hidden(fn (Get $get) => $get('responsible_type') !== ActivityResponsibleType::OTHER->value)
                                            ->searchable()
                                            ->preload(),
                                    ]),
                                Forms\Components\Section::make('Delay Information')
                                    ->schema([
                                        Forms\Components\TextInput::make('delay_count')
                                            ->label('Delay Count')
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0),
                                        Forms\Components\Select::make('delay_unit')
                                            ->label('Delay Unit')
                                            ->searchable()
                                            ->preload()
                                            ->default(ActivityDelayUnit::DAYS->value)
                                            ->options(ActivityDelayUnit::options()),
                                        Forms\Components\Select::make('delay_from')
                                            ->label('Delay From')
                                            ->searchable()
                                            ->preload()
                                            ->default(ActivityDelayInterval::BEFORE_PLAN_DATE->value)
                                            ->options(ActivityDelayInterval::options())
                                            ->helperText('Source of delay calculation'),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('activityType.name')
                    ->label('Activity Type')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('summary')
                    ->label('Summary')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible_type')
                    ->label('Assignment')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('responsible.name')
                    ->label('Assigned To')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('delay_count')
                    ->label('Interval')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('delay_unit')
                    ->label('Unit')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('delay_from')
                    ->label('Interval')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('activity_type_id')
                    ->label('Activity Type')
                    ->options(ActivityType::pluck('name', 'id')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\Filter::make('has_delay')
                    ->label('Has Delay')
                    ->query(fn ($query) => $query->whereNotNull('delay_count')),
            ])
            ->groups([
                Tables\Grouping\Group::make('responsible.name')
                    ->label('Activity Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('responsible_type')
                    ->label('Responsible Type')
                    ->collapsible(),
                Tables\Grouping\Group::make('created_at')
                    ->label('Created At')
                    ->collapsible(),
                Tables\Grouping\Group::make('updated_at')
                    ->label('Update At')
                    ->date()
                    ->collapsible(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth(MaxWidth::FitContent)
                    ->mutateFormDataUsing(function (array $data): array {
                        return [
                            ...$data,
                            'sort'       => ActivityPlanTemplate::max('sort') + 1,
                            'creator_id' => Auth::user()->id,
                        ];
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->modalWidth(MaxWidth::FitContent)
                        ->mutateFormDataUsing(function (array $data): array {
                            return [
                                ...$data,
                                'sort'       => ActivityPlanTemplate::max('sort') + 1,
                                'creator_id' => Auth::user()->id,
                            ];
                        }),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ])
            ->reorderable('sort');
    }
}
