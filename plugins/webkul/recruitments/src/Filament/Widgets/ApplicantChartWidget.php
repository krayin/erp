<?php

namespace Webkul\Recruitment\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Illuminate\Support\Carbon;
use Webkul\Recruitment\Models\Applicant;
use Webkul\Employee\Models\Department;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Recruitment\Models\Stage;
use Filament\Pages\Dashboard as BaseDashboard;

class ApplicantChartWidget extends ChartWidget
{
    use BaseDashboard\Concerns\HasFiltersForm;

    protected static ?string $heading = 'Applicants Overview';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $maxHeight = '400px';

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Select::make('selectedJobs')
                            ->label('Job Positions')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn() => EmployeeJobPosition::where('is_active', true)->pluck('name', 'id'))
                            ->reactive(),

                        Select::make('selectedDepartments')
                            ->label('Departments')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn() => Department::pluck('name', 'id'))
                            ->reactive(),

                        Select::make('selectedCompanies')
                            ->label('Companies')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn() => Company::pluck('name', 'id'))
                            ->reactive(),

                        Select::make('selectedStages')
                            ->label('Stages')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn() => Stage::pluck('name', 'id'))
                            ->reactive(),

                        Select::make('selectedRecruiters')
                            ->label('Recruiters')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->options(fn() => User::whereHas('applicants')->pluck('name', 'id'))
                            ->reactive(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'all' => 'All Statuses',
                                'ongoing' => 'Ongoing',
                                'hired' => 'Hired',
                                'refused' => 'Refused',
                                'archived' => 'Archived',
                            ])
                            ->default('all')
                            ->reactive(),

                        DatePicker::make('startDate')
                            ->label('Start Date')
                            ->maxDate(fn(Get $get) => $get('endDate') ?: now())
                            ->default(now()->subMonth()->format('Y-m-d'))
                            ->native(false),

                        DatePicker::make('endDate')
                            ->label('End Date')
                            ->minDate(fn(Get $get) => $get('startDate') ?: now())
                            ->maxDate(now())
                            ->default(now())
                            ->native(false),
                    ])
                    ->columns(3),
            ]);
    }

    protected function getData(): array
    {
        $query = Applicant::query();

        // Apply filters
        if ($this->filterFormData['selectedJobs'] ?? null) {
            $query->whereIn('job_id', $this->filterFormData['selectedJobs']);
        }

        if ($this->filterFormData['selectedDepartments'] ?? null) {
            $query->whereIn('department_id', $this->filterFormData['selectedDepartments']);
        }

        if ($this->filterFormData['selectedCompanies'] ?? null) {
            $query->whereIn('company_id', $this->filterFormData['selectedCompanies']);
        }

        if ($this->filterFormData['selectedStages'] ?? null) {
            $query->whereIn('stage_id', $this->filterFormData['selectedStages']);
        }

        if ($this->filterFormData['selectedRecruiters'] ?? null) {
            $query->whereIn('recruiter_id', $this->filterFormData['selectedRecruiters']);
        }

        // Date range filter
        if ($this->filterFormData['startDate'] ?? null) {
            $query->where('created_at', '>=', Carbon::parse($this->filterFormData['startDate'])->startOfDay());
        }

        if ($this->filterFormData['endDate'] ?? null) {
            $query->where('created_at', '<=', Carbon::parse($this->filterFormData['endDate'])->endOfDay());
        }

        // Get statistics
        $stats = $query->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN refuse_reason_id IS NOT NULL THEN 1 ELSE 0 END) as refused,
            SUM(CASE WHEN date_closed IS NOT NULL THEN 1 ELSE 0 END) as hired,
            SUM(CASE WHEN is_active = 0 OR deleted_at IS NOT NULL THEN 1 ELSE 0 END) as archived,
            SUM(CASE
                WHEN refuse_reason_id IS NULL
                AND date_closed IS NULL
                AND is_active = 1
                AND deleted_at IS NULL THEN 1
                ELSE 0
            END) as ongoing
        ')->first();

        // Filter by status if selected
        $data = match ($this->filterFormData['status'] ?? 'all') {
            'ongoing' => ['Ongoing' => $stats->ongoing ?? 0],
            'hired' => ['Hired' => $stats->hired ?? 0],
            'refused' => ['Refused' => $stats->refused ?? 0],
            'archived' => ['Archived' => $stats->archived ?? 0],
            default => [
                'Ongoing' => $stats->ongoing ?? 0,
                'Hired' => $stats->hired ?? 0,
                'Refused' => $stats->refused ?? 0,
                'Archived' => $stats->archived ?? 0,
            ],
        };

        return [
            'datasets' => [
                [
                    'label' => 'Applicants by Status',
                    'data' => array_values($data),
                    'backgroundColor' => array_map(fn($key) => match ($key) {
                        'Ongoing' => '#3b82f6',  // blue
                        'Hired' => '#22c55e',    // green
                        'Refused' => '#ef4444',  // red
                        'Archived' => '#94a3b8', // gray
                    }, array_keys($data)),
                ],
            ],
            'labels' => array_keys($data),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'stepSize' => 1,
                        'precision' => 0,
                    ],
                ],
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
        ];
    }
}
