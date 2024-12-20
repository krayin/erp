<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\TaskStageResource;

class TaskStagesRelationManager extends RelationManager
{
    protected static string $relationship = 'taskStages';

    public function form(Form $form): Form
    {
        return TaskStageResource::form($form);
    }

    public function table(Table $table): Table
    {
        return TaskStageResource::table($table)
            ->filters([])
            ->groups([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Task Stage')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    }),
            ]);
    }
}
