<?php

namespace Webkul\Project\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\View\LegacyComponents\Widget;
use Webkul\Partner\Models\Partner;
use Webkul\Project\Filament\Widgets;
use Webkul\Project\Models\Project;
use Webkul\Project\Models\Tag;
use Webkul\Security\Models\User;
use Webkul\Support\Filament\Clusters\Dashboard as DashboardCluster;

class Dashboard extends BaseDashboard
{
    use BaseDashboard\Concerns\HasFiltersForm;

    protected static string $routePath = 'project';

    protected static ?string $navigationLabel = 'Project';

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $cluster = DashboardCluster::class;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('selectedProjects')
                            ->label('Project')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Project::pluck('name', 'id'))
                            ->placeholder('Projects')
                            ->reactive(),
                        Select::make('selectedAssignees')
                            ->label('Assignees')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => User::pluck('name', 'id'))
                            ->reactive(),
                        Select::make('selectedTags')
                            ->label('Tags')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Tag::pluck('name', 'id'))
                            ->reactive(),
                        Select::make('selectedPartners')
                            ->label('Customer')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn () => Partner::pluck('name', 'id'))
                            ->reactive(),
                        DatePicker::make('startDate')
                            ->label('Start Date')
                            ->maxDate(fn (Get $get) => $get('endDate') ?: now())
                            ->default(now()->subMonth()->format('Y-m-d'))
                            ->native(false),
                        DatePicker::make('endDate')
                            ->label('End Date')
                            ->minDate(fn (Get $get) => $get('startDate') ?: now())
                            ->maxDate(now())
                            ->default(now())
                            ->native(false),
                    ])
                    ->columns(3),
            ]);
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            Widgets\StatsOverviewWidget::class,
            Widgets\TaskByStageChart::class,
            Widgets\TaskByStateChart::class,
            Widgets\TopAssigneesWidget::class,
            Widgets\TopProjectsWidget::class,
        ];
    }
}
