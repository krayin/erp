<?php

namespace Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource\Pages;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;
use Webkul\TimeOff\Filament\Clusters\Configurations\Resources\AccrualPlanResource;

class ManageMilestone extends ManageRelatedRecords
{
    protected static string $resource = AccrualPlanResource::class;

    protected static string $relationship = 'leaveAccrualLevels';

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    public static function getNavigationLabel(): string
    {
        return __('Manage Milestones');
    }

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
