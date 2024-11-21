<?php

namespace Webkul\Chatter\Filament\Resources;

use Webkul\Chatter\Filament\Resources\TaskResource\Pages;
use Webkul\Chatter\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Chatter\Enums\TaskStatus;
use Webkul\Field\Filament\Traits\HasCustomFields;

class TaskResource extends Resource
{
    use HasCustomFields;

    protected static ?string $model = Task::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
    
                Forms\Components\Section::make('Additional Information')
                    ->description('This is the customer fields information')
                    ->schema(static::getCustomFormFields())
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(static::mergeCustomTableColumns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->formatStateUsing(fn ($state) => TaskStatus::options()[$state])
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
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints(static::getTableQueryBuilderConstraints()),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->modifyQueryUsing(function ($query) {
                $user = auth()->user();

                // TODO: Implement a more robust way to handle this
                if ($user->id == 1) {
                    return;
                }
                
                $query->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhereHas('followers', function ($query) use ($user) {
                              $query->where('user_id', $user->id);
                          });
                });
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
