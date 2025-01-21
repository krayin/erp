<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class MilestoneRelationManager extends RelationManager
{
    protected static string $relationship = 'leaveAccrualLevels';

    public function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([])
            ->groups([])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([]);
    }
}
