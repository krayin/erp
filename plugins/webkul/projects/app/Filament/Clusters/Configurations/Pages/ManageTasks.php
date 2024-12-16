<?php

namespace Webkul\Project\Filament\Clusters\Configurations\Pages;

use Webkul\Security\Filament\Clusters\Settings;
use Webkul\Project\Settings\TaskSettings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SettingsPage;

class ManageTasks extends SettingsPage
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Project';

    protected static string $settings = TaskSettings::class;

    protected static ?string $cluster = Settings::class;

    public function getBreadcrumbs(): array
    {
        return [
            __('security::app.filament.clusters.settings.name'),
        ];
    }

    public function getTitle(): string
    {
        return 'Manage Tasks';
    }

    public static function getNavigationLabel(): string
    {
        return 'Manage Tasks';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('enable_recurring_tasks')
                    ->label('Enable Recurring Tasks')
                    ->helperText('Auto-generate tasks for regular activities')
                    ->required(),
                Forms\Components\Toggle::make('enable_task_dependencies')
                    ->label('Enable Task Dependencies')
                    ->helperText('Determine the order in which to perform tasks')
                    ->required(),
                Forms\Components\Toggle::make('enable_project_stages')
                    ->label('Enable Project Stages')
                    ->helperText('Track the progress of your projects')
                    ->required(),
                Forms\Components\Toggle::make('enable_milestones')
                    ->label('Enable Milestones')
                    ->helperText('Track major progress points that must be reached to achieve success')
                    ->required(),
            ]);
    }
}
