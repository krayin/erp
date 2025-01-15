<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

use Webkul\Recruitment\Filament\Clusters\Applications;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\JobByPositionResource\Pages;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Webkul\Employee\Filament\Clusters\Configurations\Resources\JobPositionResource;
use Webkul\Employee\Models\EmployeeJobPosition;
use Webkul\Recruitment\Models\Applicant;

class JobByPositionResource extends Resource
{
    protected static ?string $model = EmployeeJobPosition::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = Applications::class;

    public static function form(Form $form): Form
    {
        return JobPositionResource::form($form);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->label(__('Name'))
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('department.manager.name')
                                ->icon('heroicon-m-briefcase')
                                ->label(__('Manager'))
                                ->sortable()
                                ->searchable(),
                        ]),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('company.name')
                                ->searchable()
                                ->label(__('employees::filament/resources/department.table.columns.company-name'))
                                ->icon('heroicon-m-building-office-2')
                                ->searchable(),
                        ])
                            ->visible(fn($record) => filled($record?->company?->name)),
                    ])->space(1),
                ])->space(4),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 2,
            ])
            ->actions([
                Tables\Actions\Action::make('applications')
                    ->label(function ($record) {
                        $totalNewApplicantCount = Applicant::where('job_id', $record->id)
                            ->where('stage_id', 1)
                            ->count();

                        return __(':count New Applications', [
                            'count' => $totalNewApplicantCount,
                        ]);
                    })
                    ->button()
                    ->color('primary')
                    ->size('sm')
                    ->action(function ($record) {
                        return redirect(ApplicantResource::getUrl('index', [
                            'tableFilters' => [
                                'queryBuilder' => [
                                    'rules' => [
                                        'dPtN' => [
                                            'type' => 'stage',
                                            'data' => [
                                                'operator' => 'isRelatedTo',
                                                'settings' => [
                                                    'values' => [1]
                                                ]
                                            ]
                                        ],
                                        'kwWd' => [
                                            'type' => 'job',
                                            'data' => [
                                                'operator' => 'isRelatedTo',
                                                'settings' => [
                                                    'values' => [$record->id]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]));
                    }),
                Tables\Actions\EditAction::make('to_recruitment')
                    ->label(function ($record) {
                        return __(':count to recruitment', [
                            'count' => $record->no_of_recruitment,
                        ]);
                    })
                    ->color('primary')
                    ->size(ActionSize::Large),
                Tables\Actions\Action::make('total_applications')
                    ->label(function ($record) {
                        $totalApplicantCount = Applicant::where('job_id', $record->id)
                            ->count();

                        return __(':count Applications', [
                            'count' => $totalApplicantCount,
                        ]);
                    })
                    ->color('primary')
                    ->size(ActionSize::Large)
                    ->action(function ($record) {
                        return redirect(ApplicantResource::getUrl('index', [
                            'tableFilters' => [
                                'queryBuilder' => [
                                    'rules' => [
                                        'kwWd' => [
                                            'type' => 'job',
                                            'data' => [
                                                'operator' => 'isRelatedTo',
                                                'settings' => [
                                                    'values' => [$record->id]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]));
                    }),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return JobPositionResource::infolist($infolist);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobByPositions::route('/'),
        ];
    }
}
