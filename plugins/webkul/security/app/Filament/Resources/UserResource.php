<?php

namespace Webkul\Security\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;
use Webkul\Security\Enums\PermissionType;
use Webkul\Security\Filament\Resources\UserResource\Pages;
use Webkul\Security\Models\User;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getModelLabel(): string
    {
        return __('security::app.filament.resources.user.title');
    }

    public static function getNavigationLabel(): string
    {
        return __('security::app.filament.resources.user.navigation.title');
    }

    public static function getNavigationGroup(): string
    {
        return __('security::app.filament.resources.user.navigation.group');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make(__('security::app.filament.resources.user.form.sections.general.title'))
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.name'))
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true),
                                        Forms\Components\TextInput::make('email')
                                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.email'))
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        Forms\Components\TextInput::make('password')
                                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.password'))
                                            ->password()
                                            ->required()
                                            ->hiddenOn('edit')
                                            ->maxLength(255)
                                            ->rule('min:8'),
                                        Forms\Components\TextInput::make('password_confirmation')
                                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.password-confirmation'))
                                            ->password()
                                            ->hiddenOn('edit')
                                            ->rule('required', fn ($get) => (bool) $get('password'))
                                            ->same('password'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Section::make(__('security::app.filament.resources.user.form.sections.permissions.title'))
                                    ->schema([
                                        Forms\Components\Select::make('roles')
                                            ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.roles'))
                                            ->relationship('roles', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                        Forms\Components\Select::make('resource_permission')
                                            ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.resource-permission'))
                                            ->options(PermissionType::options())
                                            ->required()
                                            ->preload()
                                            ->searchable(),
                                        Forms\Components\Select::make('teams')
                                            ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.teams'))
                                            ->relationship('teams', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                    ])
                                    ->columns(2),
                            ])
                            ->columnSpan(['lg' => 2]),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Section::make('Language & Status')
                                    ->schema([
                                        Forms\Components\Select::make('language')
                                            ->label(__('Preferred Language'))
                                            ->options([
                                                'en' => __('English'),
                                            ])
                                            ->searchable(),
                                        Forms\Components\Toggle::make('is_active')
                                            ->label(__('Active Status'))
                                            ->default(true),
                                    ])
                                    ->columns(1),

                                Forms\Components\Section::make('Multi Company')
                                    ->schema([
                                        Forms\Components\Select::make('allowed_companies')
                                            ->label(__('Allowed Companies'))
                                            ->relationship('allowedCompanies', 'name')
                                            ->multiple()
                                            ->preload()
                                            ->searchable(),
                                        Forms\Components\Select::make('default_company_id')
                                            ->label(__('Default Company'))
                                            ->relationship('defaultCompany', 'name')
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ])
                            ->columnSpan(['lg' => 1]),
                    ])
                    ->columns(3),
            ])
            ->columns('full');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('security::app.filament.resources.user.table.columns.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('security::app.filament.resources.user.table.columns.email'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('teams.name')
                    ->label(__('security::app.filament.resources.user.table.columns.teams')),
                Tables\Columns\TextColumn::make('roles.name')
                    ->sortable()
                    ->label(__('security::app.filament.resources.user.table.columns.role')),
                Tables\Columns\TextColumn::make('resource_permission')
                    ->label(__('security::app.filament.resources.user.table.columns.resource-permission'))
                    ->formatStateUsing(fn ($state) => PermissionType::options()[$state] ?? $state)
                    ->sortable(),
                Tables\Columns\TextColumn::make('defaultCompany.name')
                    ->label(__('Default Company'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('allowedCompanies.name')
                    ->label(__('Allowed Companies'))
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('security::app.filament.resources.user.table.columns.created-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('security::app.filament.resources.user.table.columns.updated-at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('resource_permission')
                    ->label(__('security::app.filament.resources.user.table.filters.resource-permission'))
                    ->searchable()
                    ->options(PermissionType::options())
                    ->preload(),
                Tables\Filters\SelectFilter::make('default_company')
                    ->relationship('defaultCompany', 'name')
                    ->label(__('Default Company'))
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('allowed_companies')
                    ->relationship('allowedCompanies', 'name')
                    ->label(__('Allowed Companies'))
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('teams')
                    ->relationship('teams', 'name')
                    ->label(__('security::app.filament.resources.user.table.filters.teams'))
                    ->options(fn (): array => Role::query()->pluck('name', 'id')->all())
                    ->multiple()
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('roles')
                    ->label(__('security::app.filament.resources.user.table.filters.roles'))
                    ->relationship('roles', 'name')
                    ->options(fn (): array => Role::query()->pluck('name', 'id')->all())
                    ->multiple()
                    ->searchable()
                    ->preload(),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\EditAction::make()
                        ->hidden(fn ($record) => $record->trashed()),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function ($query) {
                $query->with('roles', 'teams', 'defaultCompany', 'allowedCompanies');
            });
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
