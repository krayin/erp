<?php

namespace Webkul\Security\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Components\Section;
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
                Section::make(__('security::app.filament.resources.user.form.sections.general.title'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.email'))
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password')
                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.password'))
                            ->password()
                            ->required()
                            ->hiddenOn('edit')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->password()
                            ->label(__('security::app.filament.resources.user.form.sections.general.fields.password-confirmation'))
                            ->hiddenOn('edit')
                            ->rule('required', fn ($get) => (bool) $get('password'))
                            ->same('password'),
                    ])
                    ->columns(2),
                Section::make(__('security::app.filament.resources.user.form.sections.permissions.title'))->schema([
                    Forms\Components\Select::make('roles')
                        ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.roles'))
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload(),
                    Forms\Components\Select::make('resource_permission')
                        ->options(PermissionType::options())
                        ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.resource-permission'))
                        ->required()
                        ->preload(),
                    Forms\Components\Select::make('teams')
                        ->relationship('teams', 'name')
                        ->label(__('security::app.filament.resources.user.form.sections.permissions.fields.teams'))
                        ->multiple()
                        ->preload(),
                ])
                    ->columns(2),
            ]);
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
                    ->label(__('security::app.filament.resources.user.table.columns.role')),
                Tables\Columns\TextColumn::make('resource_permission')
                    ->label(__('security::app.filament.resources.user.table.columns.resource-permission'))
                    ->sortable(),
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
            ->defaultSort('created_at', 'desc');
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
