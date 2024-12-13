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

    public static function getModelLabel(): string
    {
        return __('chatter::app.filament.resources.task.label');
    }

    public static function getNavigationLabel(): string
    {
        return __('chatter::app.filament.resources.task.navigation.title');
    }

    public static function form(Form $form): Form
    {
        $formSchema = [
            Forms\Components\Section::make(__('chatter::app.filament.resources.task.form.section.task-details.title'))
                ->description(__('chatter::app.filament.resources.task.form.section.task-details.description'))
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label(__('chatter::app.filament.resources.task.form.section.task-details.schema.title'))
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label(__('chatter::app.filament.resources.task.form.section.task-details.schema.description'))
                        ->rows(4),
                ]),

            Forms\Components\Section::make(__('chatter::app.filament.resources.task.form.section.task-status.title'))
                ->description(__('chatter::app.filament.resources.task.form.section.task-status.description'))
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label(__('chatter::app.filament.resources.task.form.section.task-status.schema.status'))
                        ->options(TaskStatus::options())
                        ->default(TaskStatus::Pending->value)
                        ->required(),
                    Forms\Components\DatePicker::make('due_date')
                        ->native(false)
                        ->label(__('chatter::app.filament.resources.task.form.section.task-status.schema.due-date')),
                ])->columns(2),

            Forms\Components\Section::make(__('chatter::app.filament.resources.task.form.section.task-assignment.title'))
                ->description(__('chatter::app.filament.resources.task.form.section.task-assignment.description'))
                ->schema([
                    Forms\Components\Hidden::make('created_by')
                        ->default(Auth::id())
                        ->label(__('chatter::app.filament.resources.task.form.section.task-assignment.schema.created-by'))
                        ->required(),
                    Forms\Components\Select::make('assigned_to')
                        ->searchable()
                        ->preload()
                        ->relationship('assignedTo', 'name')
                        ->label(__('chatter::app.filament.resources.task.form.section.task-assignment.schema.assigned-to'))
                        ->nullable(),
                    Forms\Components\Select::make('followers')
                        ->label(__('chatter::app.filament.resources.task.form.section.task-assignment.schema.followers'))
                        ->multiple()
                        ->relationship('followers', 'name')
                        ->preload(),
                ])->columns(2),
        ];

        if (count(static::getCustomFormFields())) {
            $formSchema[] = Forms\Components\Section::make(__('chatter::app.filament.resources.task.form.section.additional-information.title'))
                ->description(__('chatter::app.filament.resources.task.form.section.additional-information.description'))
                ->schema(static::getCustomFormFields())
                ->columns(2);
        }

        return $form->schema($formSchema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('chatter::app.filament.resources.task.table.columns.title'))->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('chatter::app.filament.resources.task.table.columns.status'))
                    ->formatStateUsing(fn ($state) => TaskStatus::options()[$state])
                    ->sortable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->label(__('chatter::app.filament.resources.task.table.columns.due-date'))
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label(__('chatter::app.filament.resources.task.table.columns.assigned-to'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label(__('chatter::app.filament.resources.task.table.columns.created-by'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('followers_count')
                    ->label(__('chatter::app.filament.resources.task.table.columns.followers-count'))
                    ->counts('followers'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('chatter::app.filament.resources.task.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('chatter::app.filament.resources.task.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]))
            ->filters(static::mergeCustomTableFilters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TaskStatus::options())
                    ->label(__('chatter::app.filament.resources.task.table.filters.status')),
                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label(__('chatter::app.filament.resources.task.table.filters.assigned-to'))
                    ->relationship('assignedTo', 'name'),
                Tables\Filters\SelectFilter::make('created_by')
                    ->relationship('createdBy', 'name')
                    ->label(__('chatter::app.filament.resources.task.table.filters.created-by')),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            Infolists\Components\Section::make(__('chatter::app.filament.resources.task.infolist.section.task-details.title'))
                ->description(__('chatter::app.filament.resources.task.infolist.section.task-details.description'))
                ->schema([
                    Infolists\Components\TextEntry::make('title')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-details.schema.title')),
                    Infolists\Components\TextEntry::make('description')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-details.schema.description'))
                        ->columns(2),
                ]),

            Infolists\Components\Section::make(__('chatter::app.filament.resources.task.infolist.section.task-status.title'))
                ->description(__('chatter::app.filament.resources.task.infolist.section.task-status.description'))
                ->schema([
                    Infolists\Components\TextEntry::make('status')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-status.schema.status'))
                        ->formatStateUsing(fn ($state): string => Str::headline($state)),
                    Infolists\Components\TextEntry::make('due_date')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-status.schema.due_date'))
                        ->date(),
                ])->columns(2),

            Infolists\Components\Section::make(__('chatter::app.filament.resources.task.infolist.section.task-assignment.title'))
                ->description(__('chatter::app.filament.resources.task.infolist.section.task-assignment.description'))
                ->schema([
                    Infolists\Components\TextEntry::make('createdBy.name')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-assignment.schema.created_by')),
                    Infolists\Components\TextEntry::make('assignedTo.name')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-assignment.schema.assigned_to')),
                    Infolists\Components\TextEntry::make('followers.name')
                        ->label(__('chatter::app.filament.resources.task.infolist.section.task-assignment.schema.followers'))
                        ->listWithLineBreaks(),
                ])->columns(2),
        ];

        if (count($entries = static::getCustomInfolistEntries())) {
            $schema[] = Infolists\Components\Section::make(__('chatter::app.filament.resources.task.infolist.section.additional-information.title'))
                ->description(__('chatter::app.filament.resources.task.infolist.section.additional-information.description'))
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
            'index'  => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'view'   => Pages\ViewTask::route('/{record}'),
            'edit'   => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
