<?php

namespace Webkul\Project\Filament\Resources\ProjectResource\Pages;

use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Webkul\Project\Filament\Clusters\Configurations\Resources\MilestoneResource;
use Webkul\Project\Filament\Resources\ProjectResource;
use Filament\Notifications\Notification;

class ManageProjectMilestones extends ManageRelatedRecords
{
    protected static string $resource = ProjectResource::class;

    protected static string $relationship = 'milestones';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return 'Milestones';
    }

    public function form(Form $form): Form
    {
        return MilestoneResource::form($form);
    }

    public function table(Table $table): Table
    {
        return MilestoneResource::table($table)
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
