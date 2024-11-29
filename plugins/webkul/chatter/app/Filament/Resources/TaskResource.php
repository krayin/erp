<?php

namespace Webkul\Chatter\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Webkul\Chatter\Enums\TaskStatus;
use Webkul\Chatter\Filament\Resources\TaskResource\Pages;
use Webkul\Chatter\Models\Task;
use Webkul\Fields\Filament\Traits\HasCustomFields;

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

            Forms\Components\Section::make('Task Assignment')
                ->description('Manage task creation and assignment')
                ->schema([
                    Forms\Components\Hidden::make('created_by')
                        ->default(Auth::id())
                        ->required(),
                    Forms\Components\Select::make('assigned_to')
                        ->searchable()
                        ->preload()
                        ->relationship('assignedTo', 'name')
                        ->label('Assigned To')
                        ->nullable(),
                    Forms\Components\Select::make('followers')
                        ->label('Followers')
                        ->multiple()
                        ->relationship('followers', 'name')
                        ->preload(),
                ])->columns(2),
        ];

        if (count(static::getCustomFormFields())) {
            $formSchema[] = Forms\Components\Section::make('Additional Information')
                ->description('This is the custom fields information')
                ->schema(static::getCustomFormFields())
                ->columns(2);
        }

        return $form->schema($formSchema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => TaskStatus::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To')
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Created By')
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
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->relationship('assignedTo', 'name')
                    ->label('Assigned To'),
                Tables\Filters\SelectFilter::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->label('Created By'),
            ]))
            ->groups(['status'])
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
            ]);
        // ->modifyQueryUsing(function ($query) {
        //     /**
        //      * @var \Webkul\Security\Models\User $user
        //      */
        //     $user = Auth::user();

        //     if ($user->resource_permission === PermissionType::GLOBAL->value) {
        //         return;
        //     }

        //     if ($user->resource_permission === PermissionType::INDIVIDUAL->value) {
        //         $query->where('created_by', $user->id)
        //             ->orWhereHas('followers', function ($followerQuery) use ($user) {
        //                 $followerQuery->where('user_id', $user->id);
        //             });
        //     }

        //     if ($user->resource_permission === PermissionType::GROUP->value) {
        //         $teamIds = $user->teams()->pluck('id');

        //         $query->where(function ($query) use ($teamIds, $user) {
        //             $query->whereHas('createdBy.teams', function ($teamQuery) use ($teamIds) {
        //                 $teamQuery->whereIn('teams.id', $teamIds);
        //             })->orWhereHas('assignedTo.teams', function ($teamQuery) use ($teamIds) {
        //                 $teamQuery->whereIn('teams.id', $teamIds);
        //             })->orWhereHas('followers', function ($followerQuery) use ($user) {
        //                 $followerQuery->where('user_id', $user->id);
        //             });
        //         });
        //     }
        // });
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        $schema = [
            Infolists\Components\Section::make('Task Details')
                ->description('Provide the basic details about the task')
                ->schema([
                    Infolists\Components\TextEntry::make('title')
                        ->label('Task Title'),
                    Infolists\Components\TextEntry::make('description')
                        ->label('Task Description')
                        ->columns(2),
                ]),

            Infolists\Components\Section::make('Task Status')
                ->description('Specify the status and due date of the task')
                ->schema([
                    Infolists\Components\TextEntry::make('status')
                        ->label('Task Status')
                        ->formatStateUsing(fn ($state): string => Str::headline($state)),
                    Infolists\Components\TextEntry::make('due_date')
                        ->label('Due Date')
                        ->date(),
                ])->columns(2),

            Infolists\Components\Section::make('Task Assignment')
                ->description('Manage task creation and assignment')
                ->schema([
                    Infolists\Components\TextEntry::make('createdBy.name'),
                    Infolists\Components\TextEntry::make('assignedTo.name')
                        ->label('Assigned To'),
                    Infolists\Components\TextEntry::make('followers.name')
                        ->label('Followers')
                        ->listWithLineBreaks(),
                ])->columns(2),
        ];

        if (count($entries = static::getCustomInfolistEntries())) {
            $schema[] = Infolists\Components\Section::make('Additional Information')
                ->description('This is the custom fields information')
                ->schema($entries)
                ->columns(2);
        }

        return $infolist->schema($schema);
    }

    public static function getRelations(): array
    {
        return [];
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
