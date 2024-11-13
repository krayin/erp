<?php

namespace Webkul\Chatter\Filament\Resources;

use Webkul\Chatter\Filament\Resources\FollowerResource\Pages;
use Webkul\Chatter\Filament\Resources\FollowerResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Webkul\Chatter\Models\Follower;

class FollowerResource extends Resource
{
    protected static ?string $model = Follower::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListFollowers::route('/'),
            'create' => Pages\CreateFollower::route('/create'),
            'edit' => Pages\EditFollower::route('/{record}/edit'),
        ];
    }
}
