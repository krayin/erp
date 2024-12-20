<?php

namespace Webkul\Project\Filament\Resources\TaskResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Webkul\Project\Filament\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Models\TaskStage;
use Webkul\Project\Enums\TaskState;

class SubTasksRelationManager extends RelationManager
{
    protected static string $relationship = 'subTasks';

    public function form(Form $form): Form
    {
        return TaskResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskResource::table($table)
            ->filters([])
            ->groups([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Task Stage')
                    ->fillForm(function (array $arguments): array {
                        return [
                            'stage_id' => TaskStage::first()?->id,
                            'state' => TaskState::IN_PROGRESS,
                            'project_id' => $this->getOwnerRecord()->project_id,
                            'milestone_id' => $this->getOwnerRecord()->milestone_id,
                            'partner_id' => $this->getOwnerRecord()->partner_id,
                            'users' => $this->getOwnerRecord()->users->pluck('id')->toArray(),
                        ];
                    })
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->modalWidth('6xl'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->modalWidth('6xl'),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed())
                        ->modalWidth('6xl'),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return TaskResource::infolist($infolist);
    }
}
