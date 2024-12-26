<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource;
use Filament\Notifications\Notification;

class MilestonesRelationManager extends RelationManager
{
    protected static string $relationship = 'milestones';

    public function form(Form $form): Form
    {
        return MilestoneResource::form($form);
    }

    public function table(Table $table): Table
    {
        return MilestoneResource::table($table)
            ->filters([])
            ->groups([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Project Milestone')
                    ->icon('heroicon-o-plus-circle')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['creator_id'] = Auth::id();

                        return $data;
                    })
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Milestone created')
                            ->body('The milestone has been created successfully.'),
                    ),
            ]);
    }
}
