<?php

namespace Webkul\Chatter\Filament\Resources;

use Webkul\Chatter\Filament\Resources\TaskResource\Pages;
use Webkul\Chatter\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Webkul\Chatter\Enums\TaskStatus;
use Webkul\Core\Enums\UserResourcePermission;
use Webkul\Field\Filament\Traits\HasCustomFields;
use Illuminate\Support\Facades\Auth;

class TaskResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        $formSchema = [
            Forms\Components\Section::make('Task Details')
                ->description('Provide the basic details about the task')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Task Title')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label('Task Description')
                        ->rows(4),
                ]),

            Forms\Components\Section::make('Task Status')
                ->description('Specify the status and due date of the task')
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label('Task Status')
                        ->options(TaskStatus::options())
                        ->default(TaskStatus::Pending->value)
                        ->required(),
                    Forms\Components\DatePicker::make('due_date')
                        ->native(false)
                        ->label('Due Date'),
                ])->columns(2),

            Forms\Components\Section::make('Assignment')
                ->description('Assign this task to a user')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->searchable()
                        ->preload()
                        ->relationship('user', 'name')
                        ->label('Assigned To')
                        ->required(),
                    Forms\Components\Select::make('followers')
                        ->label('Followers')
                        ->multiple()
                        ->relationship('followers', 'name')
                        ->preload()
                ])->columns(2),
        ];

        if (count(static::getCustomFormFields())) {
            $formSchema[] = Forms\Components\Section::make('Additional Information')
                ->description('This is the customer fields information')
                ->schema(static::getCustomFormFields())
                ->columns(2);
        }

        return $form
            ->schema($formSchema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn($state) => TaskStatus::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Assigned To')
                    ->sortable(),
                Tables\Columns\TextColumn::make('followers_count')->counts('followers'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters(static::mergeCustomTableFilters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::options())
                    ->label('Task Status'),
                Tables\Filters\SelectFilter::make('user_id')
                    ->relationship('user', 'name')
                    ->label('Assigned To'),
            ]))
            ->groups([
                'status',
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->modifyQueryUsing(function ($query) {
                $user = Auth::user();

                switch ($user->resource_permission) {
                    case UserResourcePermission::GLOBAL->value:
                        break;

                    case UserResourcePermission::GROUP->value:
                        $teamIds = $user->teams()->pluck('id');

                        $query->where(function (Builder $query) use ($teamIds, $user) {
                            $query
                                ->whereHas('user', function (Builder $subQuery) use ($teamIds) {
                                    $subQuery->whereHas('teams', function (Builder $teamQuery) use ($teamIds) {
                                        $teamQuery->whereIn('teams.id', $teamIds);
                                    });
                                })
                                ->orWhereHas('followers', function (Builder $followerQuery) use ($user) {
                                    $followerQuery->where('user_id', $user->id);
                                })
                                ->orWhere('user_id', $user->id);
                        });

                        break;

                    case UserResourcePermission::INDIVIDUAL->value:
                        $query->where(function (Builder $query) use ($user) {
                            $query
                                ->where('user_id', $user->id)
                                ->orWhereHas('followers', function (Builder $followerQuery) use ($user) {
                                    $followerQuery->where('user_id', $user->id);
                                });
                        });

                        break;
                }
            });
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view' => Pages\ViewTask::route('/{record}'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
