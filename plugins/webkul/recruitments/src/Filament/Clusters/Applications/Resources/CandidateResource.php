<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationGroup;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\HtmlString;
use Webkul\Partner\Models\Partner;
use Webkul\Recruitment\Filament\Clusters\Applications;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource\Pages;
use Webkul\Recruitment\Filament\Clusters\Applications\Resources\CandidateResource\RelationManagers;
use Webkul\Recruitment\Models\Candidate;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $cluster = Applications::class;

    public static function getSubNavigationPosition(): SubNavigationPosition
    {
        if (str_contains(Route::currentRouteName(), 'index')) {
            return SubNavigationPosition::Start;
        }

        return SubNavigationPosition::Top;
    }

    public static function getModelLabel(): string
    {
        return __('recruitments::filament/clusters/applications/resources/candidate.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('recruitments::filament/clusters/applications/resources/candidate.navigation.group');
    }

    public static function getNavigationLabel(): string
    {
        return __('recruitments::filament/clusters/applications/resources/candidate.navigation.title');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'partner_name',
            'email_from',
            'phone_sanitized',
            'company.name',
            'degree.name',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.title'))
                            ->schema([
                                Forms\Components\TextInput::make('partner_name')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.full-name'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('partner_id')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.contact'))
                                    ->relationship('partner', 'name')
                                    ->searchable()
                                    ->afterStateUpdated(function (Set $set, Get $get) {
                                        $set('email_from', Partner::find($get('partner_id'))?->email);
                                        $set('phone_sanitized', Partner::find($get('partner_id'))?->phone);
                                    })
                                    ->preload()
                                    ->live(),
                                Forms\Components\TextInput::make('email_from')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.email'))
                                    ->email()
                                    ->live()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone_sanitized')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.phone'))
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('linkedin_profile')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.basic-information.fields.linkedin'))
                                    ->url()
                                    ->maxLength(255),
                            ])
                            ->columns(2),
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.title'))
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.company')),
                                Forms\Components\Select::make('degree_id')
                                    ->relationship('degree', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.degree')),
                                Forms\Components\Select::make('recruitments_candidate_applicant_categories')
                                    ->multiple()
                                    ->relationship('categories', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.tags')),
                                Forms\Components\Select::make('manager_id')
                                    ->relationship('manager', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.manager')),
                                Forms\Components\DatePicker::make('availability_date')
                                    ->native(false)
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.additional-details.fields.availability-date')),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(['lg' => 2]),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.status-and-evaluation.title'))
                            ->schema([
                                Forms\Components\Toggle::make('is_active')
                                    ->label(__('Status'))
                                    ->inline(false)
                                    ->default(true),
                                Forms\Components\Placeholder::make('evaluation')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.status-and-evaluation.fields.evaluation'))
                                    ->content(function ($record) {
                                        $html = '<div class="flex gap-1" style="color: rgb(217 119 6);">';

                                        for ($i = 1; $i <= 3; $i++) {
                                            $iconType = $i <= $record?->priority ? 'heroicon-s-star' : 'heroicon-o-star';
                                            $html .= view('filament::components.icon', [
                                                'icon'  => $iconType,
                                                'class' => 'w-5 h-5',
                                            ])->render();
                                        }

                                        $html .= '</div>';

                                        return new HtmlString($html);
                                    }),
                            ]),
                        Forms\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.communication.title'))
                            ->schema([
                                Forms\Components\TextInput::make('email_cc')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.communication.fields.cc-email'))
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('message_bounced')
                                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.form.sections.communication.fields.email-bounced'))
                                    ->default(false),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('partner_name')
                            ->weight(FontWeight::Bold)
                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.name'))
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('categories.name')
                                ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.tags'))
                                ->badge()
                                ->weight(FontWeight::Bold)
                                ->state(function (Candidate $record): array {
                                    return $record->categories->map(fn ($category) => [
                                        'label' => $category->name,
                                        'color' => $category->color ?? 'primary',
                                    ])->toArray();
                                })
                                ->formatStateUsing(fn ($state) => $state['label'])
                                ->color(fn ($state) => Color::hex($state['color'])),
                            Tables\Columns\TextColumn::make('priority')
                                ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.columns.evaluation'))
                                ->color('warning')
                                ->formatStateUsing(function ($state) {
                                    $html = '<div class="flex gap-1" style="margin-top: 6px;">';
                                    for ($i = 1; $i <= 3; $i++) {
                                        $iconType = $i <= $state ? 'heroicon-s-star' : 'heroicon-o-star';
                                        $html .= view('filament::components.icon', [
                                            'icon'  => $iconType,
                                            'class' => 'w-5 h-5',
                                        ])->render();
                                    }
                                    $html .= '</div>';

                                    return new HtmlString($html);
                                }),
                        ])
                            ->visible(fn ($record) => filled($record?->categories?->count())),
                    ])->space(1),
                ])
                    ->space(4),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.company')),
                Tables\Filters\SelectFilter::make('degree')
                    ->relationship('degree', 'name')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.degree')),
                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        '0' => __('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority-options.low'),
                        '1' => __('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority-options.medium'),
                        '2' => __('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority-options.high'),
                    ])
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.priority')),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('recruitments::filament/clusters/applications/resources/candidate.table.filters.status')),
            ])
            ->actions([
                // Tables\Actions\Action::make('star2')
                //     ->hiddenLabel()
                //     ->icon(fn ($record) => $record->priority >= 2 ? 'heroicon-s-star' : 'heroicon-o-star')
                //     ->color(fn ($record) => $record->priority >= 2 ? 'warning' : 'gray')
                //     ->size('md')
                //     ->tooltip('Evaluation: Very Good')
                //     ->action(fn ($record) => $record->update(['priority' => 2])),
                // Tables\Actions\Action::make('star3')
                //     ->hiddenLabel()
                //     ->icon(fn ($record) => $record->priority >= 3 ? 'heroicon-s-star' : 'heroicon-o-star')
                //     ->color(fn ($record) => $record->priority >= 3 ? 'warning' : 'gray')
                //     ->size('md')
                //     ->tooltip('Evaluation: Excellent')
                //     ->action(fn ($record) => $record->update(['priority' => 3])),
                // Tables\Actions\ActionGroup::make([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('recruitments::filament/clusters/applications/resources/candidate.table.actions.delete.notification.title'))
                            ->body(__('recruitments::filament/clusters/applications/resources/candidate.table.actions.delete.notification.body'))
                    ),
                // ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('recruitments::filament/clusters/applications/resources/candidate.table.bulk-actions.delete.notification.title'))
                                ->body(__('recruitments::filament/clusters/applications/resources/candidate.table.bulk-actions.delete.notification.body'))
                        ),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->icon('heroicon-o-plus-circle')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('recruitments::filament/clusters/applications/resources/candidate.table.empty-state-actions.create.notification.title'))
                            ->body(__('recruitments::filament/clusters/applications/resources/candidate.table.empty-state-actions.create.notification.body'))
                    ),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(['default' => 3])
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.basic-information.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('partner_name')
                                            ->icon('heroicon-o-user')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.basic-information.entries.full-name')),
                                        Infolists\Components\TextEntry::make('partner.name')
                                            ->icon('heroicon-o-identification')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.basic-information.entries.contact')),
                                        Infolists\Components\TextEntry::make('email_from')
                                            ->icon('heroicon-o-envelope')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.basic-information.entries.email')),
                                        Infolists\Components\TextEntry::make('phone_sanitized')
                                            ->icon('heroicon-o-phone')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.basic-information.entries.phone')),
                                        Infolists\Components\TextEntry::make('linkedin_profile')
                                            ->icon('heroicon-o-link')
                                            ->placeholder('—')
                                            ->url(fn ($record) => $record->linkedin_profile)
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.basic-information.entries.linkedin')),
                                    ])
                                    ->columns(2),
                                Infolists\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.additional-details.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('company.name')
                                            ->icon('heroicon-o-building-office')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.additional-details.entries.company')),
                                        Infolists\Components\TextEntry::make('degree.name')
                                            ->icon('heroicon-o-academic-cap')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.additional-details.entries.degree')),
                                        Infolists\Components\TextEntry::make('categories.name')
                                            ->icon('heroicon-o-tag')
                                            ->placeholder('—')
                                            ->state(function (Candidate $record): array {
                                                return $record->categories->map(fn ($category) => [
                                                    'label' => $category->name,
                                                    'color' => $category->color ?? 'primary',
                                                ])->toArray();
                                            })
                                            ->badge()
                                            ->formatStateUsing(fn ($state) => $state['label'])
                                            ->color(fn ($state) => Color::hex($state['color']))
                                            ->listWithLineBreaks()
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.additional-details.entries.tags')),
                                        Infolists\Components\TextEntry::make('manager.name')
                                            ->icon('heroicon-o-user-circle')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.additional-details.entries.manager')),
                                        Infolists\Components\TextEntry::make('availability_date')
                                            ->icon('heroicon-o-calendar')
                                            ->placeholder('—')
                                            ->date()
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.additional-details.entries.availability-date')),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(2),
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.status-and-evaluation.title'))
                                    ->schema([
                                        Infolists\Components\IconEntry::make('is_active')
                                            ->boolean()
                                            ->label(__('Status')),
                                        Infolists\Components\TextEntry::make('priority')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.status-and-evaluation.entries.evaluation'))
                                            ->formatStateUsing(function ($state) {
                                                $html = '<div class="flex gap-1" style="color: rgb(217 119 6);">';
                                                for ($i = 1; $i <= 3; $i++) {
                                                    $iconType = $i <= $state ? 'heroicon-s-star' : 'heroicon-o-star';
                                                    $html .= view('filament::components.icon', [
                                                        'icon'  => $iconType,
                                                        'class' => 'w-5 h-5',
                                                    ])->render();
                                                }

                                                $html .= '</div>';

                                                return new HtmlString($html);
                                            })
                                            ->placeholder('—'),
                                    ]),
                                Infolists\Components\Section::make(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.communication.title'))
                                    ->schema([
                                        Infolists\Components\TextEntry::make('email_cc')
                                            ->icon('heroicon-o-envelope')
                                            ->placeholder('—')
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.communication.entries.cc-email')),
                                        Infolists\Components\IconEntry::make('message_bounced')
                                            ->boolean()
                                            ->label(__('recruitments::filament/clusters/applications/resources/candidate.infolist.sections.communication.entries.email-bounced')),
                                    ]),
                            ])
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewCandidate::class,
            Pages\EditCandidate::class,
            Pages\ManageSkill::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Manage Skills', [
                RelationManagers\SkillsRelationManager::class,
            ])
                ->icon('heroicon-o-bolt'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit'   => Pages\EditCandidate::route('/{record}/edit'),
            'view'   => Pages\ViewCandidate::route('/{record}'),
            'skills' => Pages\ManageSkill::route('/{record}/skills'),
        ];
    }
}
