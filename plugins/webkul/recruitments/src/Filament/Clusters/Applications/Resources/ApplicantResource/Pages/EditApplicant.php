<?php

namespace Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource\Pages;

use Webkul\Recruitment\Filament\Clusters\Applications\Resources\ApplicantResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Webkul\Recruitment\Models\RefuseReason;

class EditApplicant extends EditRecord
{
    protected static string $resource = ApplicantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Action::make('Refuse')
                ->modalIcon('heroicon-s-bug-ant')
                ->hidden(fn($record) => $record->refuse_reason_id)
                ->modalHeading('Refuse Reason')
                ->form(function (Form $form, $record) {
                    return $form->schema([
                        Forms\Components\ToggleButtons::make('refuse_reason_id')
                            ->hiddenLabel()
                            ->inline()
                            ->live()
                            ->options(RefuseReason::all()->pluck('name', 'id')),
                        Forms\Components\Toggle::make('notify')
                            ->inline()
                            ->live()
                            ->default(true)
                            ->visible(fn(Get $get) => $get('refuse_reason_id'))
                            ->label('Notify'),
                        Forms\Components\TextInput::make('email')
                            ->visible(fn(Get $get) => $get('notify') && $get('refuse_reason_id'))
                            ->default($record->candidate->email_from)
                            ->label('Email To')
                    ]);
                })
                ->action(function (array $data, $record) {
                    $record->update([
                        'refuse_reason_id' => $data['refuse_reason_id'],
                        'refuse_date'      => now(),
                        'date_closed'      => null,
                    ]);
                }),
            Action::make('Restore')
                ->hidden(fn($record) => ! $record->refuse_reason_id)
                ->modalHeading('Restore Applicant from refuse')
                ->requiresConfirmation()
                ->action(function ($record) {
                    $record->update([
                        'refuse_reason_id' => null,
                        'refuse_date'      => null,
                        'date_closed'      => null,
                        'stage_id'         => null,
                    ]);
                })
        ];
    }
}
