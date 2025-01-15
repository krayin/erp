<?php

namespace Webkul\Contact\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
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
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Webkul\Contact\Filament\Resources\PartnerResource\Pages;
use Webkul\Contact\Filament\Resources\PartnerResource\RelationManagers;
use Webkul\Partner\Enums\AccountType;
use Webkul\Partner\Models\Partner;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;

    protected static ?string $slug = 'contact/contacts';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationLabel(): string
    {
        return __('contacts::filament/resources/partner.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('contacts::filament/resources/partner.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('contacts::filament/resources/partner.form.sections.general.title'))
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Radio::make('account_type')
                                            ->hiddenLabel()
                                            ->inline()
                                            ->columnSpan(2)
                                            ->options([
                                                AccountType::INDIVIDUAL->value => AccountType::options()[AccountType::INDIVIDUAL->value],
                                                AccountType::COMPANY->value    => AccountType::options()[AccountType::COMPANY->value],
                                            ])
                                            ->default(AccountType::INDIVIDUAL->value)
                                            ->live(),
                                        Forms\Components\TextInput::make('name')
                                            ->hiddenLabel()
                                            ->required()
                                            ->maxLength(255)
                                            ->columnSpan(2)
                                            ->placeholder(fn(Forms\Get $get): string => $get('account_type') === AccountType::INDIVIDUAL->value ? 'Jhon Doe' : 'ACME Corp')
                                            ->extraInputAttributes(['style' => 'font-size: 1.5rem;height: 3rem;']),
                                        Forms\Components\Select::make('parent_id')
                                            ->label(__('contacts::filament/resources/partner.form.sections.general.fields.company'))
                                            ->relationship(
                                                name: 'parent',
                                                titleAttribute: 'name',
                                                // modifyQueryUsing: fn (Builder $query) => $query->where('account_type', AccountType::COMPANY->value),
                                            )
                                            ->visible(fn(Forms\Get $get): bool => $get('account_type') === AccountType::INDIVIDUAL->value)
                                            ->searchable()
                                            ->preload()
                                            ->columnSpan(2)
                                            ->createOptionForm(fn(Form $form): Form => self::form($form))
                                            ->editOptionForm(fn(Form $form): Form => self::form($form))
                                            ->createOptionAction(function (Forms\Components\Actions\Action $action) {
                                                $action
                                                    ->fillForm(function (array $arguments): array {
                                                        return [
                                                            'account_type' => AccountType::COMPANY->value,
                                                        ];
                                                    })
                                                    ->mutateFormDataUsing(function (array $data) {
                                                        $data['account_type'] = AccountType::COMPANY->value;

                                                        return $data;
                                                    });
                                            }),
                                    ]),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\FileUpload::make('avatar')
                                            ->image()
                                            ->hiddenLabel()
                                            ->imageResizeMode('cover')
                                            ->imageEditor()
                                            ->avatar()
                                            ->directory('partners/avatar')
                                            ->visibility('private'),
                                    ]),
                            ])->columns(2),
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('tax_id')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.tax-id'))
                                    ->placeholder('e.g. 29ABCDE1234F1Z5')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('job_title')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.job-title'))
                                    ->placeholder('e.g. CEO')
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.phone'))
                                    ->tel()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('mobile')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.mobile'))
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.email'))
                                    ->email()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('website')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.website'))
                                    ->maxLength(255)
                                    ->url(),
                                Forms\Components\Select::make('title_id')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.title'))
                                    ->relationship('title', 'name')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('contacts::filament/resources/partner.form.sections.general.fields.name'))
                                            ->required()
                                            ->unique('partners_titles'),
                                        Forms\Components\TextInput::make('short_name')
                                            ->label(__('contacts::filament/resources/partner.form.sections.general.fields.short-name'))
                                            ->label('Short Name')
                                            ->required()
                                            ->unique('partners_titles'),
                                        Forms\Components\Hidden::make('creator_id')
                                            ->default(Auth::user()->id),
                                    ]),
                                Forms\Components\Select::make('tags')
                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.tags'))
                                    ->relationship(name: 'tags', titleAttribute: 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\Group::make()
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.name'))
                                                    ->required()
                                                    ->unique('partners_tags'),
                                                Forms\Components\ColorPicker::make('color')
                                                    ->label(__('contacts::filament/resources/partner.form.sections.general.fields.color'))
                                                    ->required(),
                                            ])
                                            ->columns(2)
                                    ]),
                            ])
                            ->columns(2),
                    ]),

                Forms\Components\Tabs::make('tabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('contacts::filament/resources/partner.form.tabs.sales-purchase.title'))
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Forms\Components\Fieldset::make('Sales')
                                    ->schema([
                                        Forms\Components\Select::make('user_id')
                                            ->label(__('contacts::filament/resources/partner.form.tabs.sales-purchase.fields.responsible'))
                                            ->relationship('user', 'name')
                                            ->searchable()
                                            ->preload()
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('contacts::filament/resources/partner.form.tabs.sales-purchase.fields.responsible-hint-text')),
                                    ])
                                    ->columns(1),

                                Forms\Components\Fieldset::make('Others')
                                    ->schema([
                                        Forms\Components\TextInput::make('company_registry')
                                            ->label(__('contacts::filament/resources/partner.form.tabs.sales-purchase.fields.company-id'))
                                            ->maxLength(255)
                                            ->hintIcon('heroicon-m-question-mark-circle', tooltip: __('contacts::filament/resources/partner.form.tabs.sales-purchase.fields.company-id-hint-text')),
                                        Forms\Components\TextInput::make('reference')
                                            ->label(__('contacts::filament/resources/partner.form.tabs.sales-purchase.fields.reference'))
                                            ->maxLength(255),
                                        Forms\Components\Select::make('industry_id')
                                            ->label(__('contacts::filament/resources/partner.form.tabs.sales-purchase.fields.industry'))
                                            ->relationship('industry', 'name'),
                                    ])
                                    ->columns(2),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpan(2),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\ImageColumn::make('avatar')
                        ->height(150)
                        ->width(200),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable(),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('parent.name')
                                ->label(__('contacts::filament/resources/partner.table.columns.parent'))
                                ->icon(fn(Partner $record) => $record->parent->account_type === AccountType::INDIVIDUAL->value ? 'heroicon-o-user' : 'heroicon-o-building-office')
                                ->tooltip(__('contacts::filament/resources/partner.table.columns.parent'))
                                ->sortable(),
                        ])
                            ->visible(fn(Partner $record) => filled($record->parent)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('job_title')
                                ->icon('heroicon-m-briefcase')
                                ->searchable()
                                ->sortable()
                                ->label('Job Title'),
                        ])
                            ->visible(fn($record) => filled($record->job_title)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('email')
                                ->icon('heroicon-o-envelope')
                                ->searchable()
                                ->sortable()
                                ->label('Work Email')
                                ->color('gray')
                                ->limit(20),
                        ])
                            ->visible(fn($record) => filled($record->email)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('phone')
                                ->icon('heroicon-o-phone')
                                ->searchable()
                                ->label('Work Phone')
                                ->color('gray')
                                ->limit(30)
                                ->sortable(),
                        ])
                            ->visible(fn($record) => filled($record->phone)),
                        Tables\Columns\Layout\Stack::make([
                            Tables\Columns\TextColumn::make('tags.name')
                                ->badge()
                                ->state(function (Partner $record): array {
                                    return $record->tags()->get()->map(fn($tag) => [
                                        'label' => $tag->name,
                                        'color' => $tag->color ?? 'primary'
                                    ])->toArray();
                                })
                                ->badge()
                                ->formatStateUsing(fn($state) => $state['label'])
                                ->color(fn($state) => Color::hex($state['color']))
                                ->weight(FontWeight::Bold),
                        ])
                            ->visible(fn($record): bool => (bool) $record->tags()->get()?->count()),
                    ])->space(1),
                ])->space(4),
            ])
            ->groups([
                Tables\Grouping\Group::make('account_type')
                    ->label(__('contacts::filament/resources/partner.table.groups.account-type'))
                    ->getTitleFromRecordUsing(fn(Partner $record): string => AccountType::options()[$record->account_type]),
                Tables\Grouping\Group::make('parent.name')
                    ->label(__('contacts::filament/resources/partner.table.groups.parent')),
                Tables\Grouping\Group::make('title.name')
                    ->label(__('contacts::filament/resources/partner.table.groups.title')),
                Tables\Grouping\Group::make('job_title')
                    ->label(__('contacts::filament/resources/partner.table.groups.job-title')),
                Tables\Grouping\Group::make('industry.name')
                    ->label(__('contacts::filament/resources/partner.table.groups.industry')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\QueryBuilder::make()
                    ->constraints([
                        Tables\Filters\QueryBuilder\Constraints\SelectConstraint::make('account_type')
                            ->label(__('contacts::filament/resources/partner.table.filters.account-type'))
                            ->multiple()
                            ->options(AccountType::options())
                            ->icon('heroicon-o-bars-2'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('name')
                            ->label(__('contacts::filament/resources/partner.table.filters.name')),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('email')
                            ->label(__('contacts::filament/resources/partner.table.filters.email'))
                            ->icon('heroicon-o-envelope'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('job_title')
                            ->label(__('contacts::filament/resources/partner.table.filters.job-title')),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('website')
                            ->label(__('contacts::filament/resources/partner.table.filters.website'))
                            ->icon('heroicon-o-globe-alt'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('tax_id')
                            ->label(__('contacts::filament/resources/partner.table.filters.tax-id'))
                            ->icon('heroicon-o-identification'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('phone')
                            ->label(__('contacts::filament/resources/partner.table.filters.phone'))
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('mobile')
                            ->label(__('contacts::filament/resources/partner.table.filters.mobile'))
                            ->icon('heroicon-o-phone'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('company_registry')
                            ->label(__('contacts::filament/resources/partner.table.filters.company-registry'))
                            ->icon('heroicon-o-clipboard'),
                        Tables\Filters\QueryBuilder\Constraints\TextConstraint::make('reference')
                            ->label(__('contacts::filament/resources/partner.table.filters.reference'))
                            ->icon('heroicon-o-hashtag'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('parent')
                            ->label(__('contacts::filament/resources/partner.table.filters.parent'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('creator')
                            ->label(__('contacts::filament/resources/partner.table.filters.creator'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('user')
                            ->label(__('contacts::filament/resources/partner.table.filters.responsible'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-user'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('title')
                            ->label(__('contacts::filament/resources/partner.table.filters.title'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            ),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('company')
                            ->label(__('contacts::filament/resources/partner.table.filters.company'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                        Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint::make('industry')
                            ->label(__('contacts::filament/resources/partner.table.filters.industry'))
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('name')
                                    ->searchable()
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-building-office'),
                    ]),
            ], layout: \Filament\Tables\Enums\FiltersLayout::Modal)
            ->filtersTriggerAction(
                fn(Tables\Actions\Action $action) => $action
                    ->slideOver(),
            )
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hidden(fn($record) => $record->trashed()),
                Tables\Actions\EditAction::make()
                    ->hidden(fn($record) => $record->trashed())
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner.table.actions.edit.notification.title'))
                            ->body(__('contacts::filament/resources/partner.table.actions.edit.notification.body')),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner.table.actions.restore.notification.title'))
                            ->body(__('contacts::filament/resources/partner.table.actions.restore.notification.body')),
                    ),
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner.table.actions.delete.notification.title'))
                            ->body(__('contacts::filament/resources/partner.table.actions.delete.notification.body')),
                    ),
                Tables\Actions\ForceDeleteAction::make()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title(__('contacts::filament/resources/partner.table.actions.force-delete.notification.title'))
                            ->body(__('contacts::filament/resources/partner.table.actions.force-delete.notification.body')),
                    ),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\RestoreBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('contacts::filament/resources/partner.table.bulk-actions.restore.notification.title'))
                                ->body(__('contacts::filament/resources/partner.table.bulk-actions.restore.notification.body')),
                        ),
                    Tables\Actions\DeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('contacts::filament/resources/partner.table.bulk-actions.delete.notification.title'))
                                ->body(__('contacts::filament/resources/partner.table.bulk-actions.delete.notification.body')),
                        ),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title(__('contacts::filament/resources/partner.table.bulk-actions.force-delete.notification.title'))
                                ->body(__('contacts::filament/resources/partner.table.bulk-actions.force-delete.notification.body')),
                        ),
                ]),
            ])
            ->contentGrid([
                'sm'  => 1,
                'md'  => 2,
                'xl'  => 3,
                '2xl' => 4,
            ])
            ->paginated([
                16,
                32,
                64,
                'all',
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('contacts::filament/resources/partner.infolist.sections.general.title'))
                    ->schema([
                        Infolists\Components\Group::make()
                            ->schema([
                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('account_type')
                                            ->badge()
                                            ->formatStateUsing(fn($state) => AccountType::options()[$state])
                                            ->color('primary'),

                                        Infolists\Components\TextEntry::make('name')
                                            ->weight(FontWeight::Bold)
                                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                        Infolists\Components\TextEntry::make('parent.name')
                                            ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.company'))
                                            ->visible(fn($record): bool => $record->account_type === AccountType::INDIVIDUAL->value),
                                    ]),

                                Infolists\Components\Group::make()
                                    ->schema([
                                        Infolists\Components\ImageEntry::make('avatar')
                                            ->circular()
                                            ->height(100)
                                            ->width(100),
                                    ]),
                            ])->columns(2),

                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('tax_id')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.tax-id'))
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('job_title')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.job-title'))
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('phone')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.phone'))
                                    ->icon('heroicon-o-phone')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('mobile')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.mobile'))
                                    ->icon('heroicon-o-device-phone-mobile')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('email')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.email'))
                                    ->icon('heroicon-o-envelope'),

                                Infolists\Components\TextEntry::make('website')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.website'))
                                    // ->url()
                                    ->icon('heroicon-o-globe-alt')
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('title.name')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.title'))
                                    ->placeholder('—'),

                                Infolists\Components\TextEntry::make('tags.name')
                                    ->label(__('contacts::filament/resources/partner.infolist.sections.general.fields.tags'))
                                    ->badge()
                                    ->state(function (Partner $record): array {
                                        return $record->tags()->get()->map(fn($tag) => [
                                            'label' => $tag->name,
                                            'color' => $tag->color ?? 'primary'
                                        ])->toArray();
                                    })
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state['label'])
                                    ->color(fn($state) => Color::hex($state['color']))
                                    ->separator(',')
                                    ->visible(fn($record): bool => (bool) $record->tags()->count()),
                            ]),
                    ]),

                Infolists\Components\Tabs::make('Tabs')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make(__('contacts::filament/resources/partner.infolist.tabs.sales-purchase.title'))
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Infolists\Components\Section::make('Sales')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('user.name')
                                            ->label(__('contacts::filament/resources/partner.infolist.tabs.sales-purchase.fields.responsible'))
                                            ->placeholder('—'),
                                    ])
                                    ->columns(1),

                                Infolists\Components\Section::make('Others')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('company_registry')
                                            ->label(__('contacts::filament/resources/partner.infolist.tabs.sales-purchase.fields.company-id'))
                                            ->placeholder('—'),

                                        Infolists\Components\TextEntry::make('reference')
                                            ->label(__('contacts::filament/resources/partner.infolist.tabs.sales-purchase.fields.reference'))
                                            ->placeholder('—'),

                                        Infolists\Components\TextEntry::make('industry.name')
                                            ->label(__('contacts::filament/resources/partner.infolist.tabs.sales-purchase.fields.industry'))
                                            ->placeholder('—'),
                                    ])
                                    ->columns(2),
                            ]),
                    ])
                    ->columnSpan(2),
            ])
            ->columns(2);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewPartner::class,
            Pages\EditPartner::class,
            Pages\ManageContacts::class,
            Pages\ManageAddresses::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationGroup::make('Contacts', [
                RelationManagers\ContactsRelationManager::class,
            ])
                ->icon('heroicon-o-users'),

            RelationGroup::make('Addresses', [
                RelationManagers\AddressesRelationManager::class,
            ])
                ->icon('heroicon-o-map-pin'),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'     => Pages\ListPartners::route('/'),
            'create'    => Pages\CreatePartner::route('/create'),
            'view'      => Pages\ViewPartner::route('/{record}'),
            'edit'      => Pages\EditPartner::route('/{record}/edit'),
            'contacts'  => Pages\ManageContacts::route('/{record}/contacts'),
            'addresses' => Pages\ManageAddresses::route('/{record}/addresses'),
        ];
    }
}
