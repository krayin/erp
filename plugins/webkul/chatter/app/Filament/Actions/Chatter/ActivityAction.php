<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Webkul\Chatter\Enums\ActivityType;
use Webkul\Security\Models\User;

class ActivityAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'activity.action';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->color('gray')
            ->outlined()
            ->form(
                fn ($form) => $form->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Select::make('activity_type')
                                ->label('Activity Type')
                                ->options(ActivityType::options())
                                ->required(),
                            Forms\Components\DatePicker::make('due_date')
                                ->label('Due Date')
                                ->native(false)
                                ->required(),
                        ])->columns(2),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('summary')
                                ->label('Summary')
                                ->required(),
                            Forms\Components\Select::make('assigned_to')
                                ->label('Assigned To')
                                ->searchable()
                                ->live()
                                ->options(User::all()->pluck('name', 'id')->toArray())
                                ->required(),
                        ])->columns(2),
                    Forms\Components\RichEditor::make('content')
                        ->hiddenLabel()
                        ->placeholder('Type your message here...')
                        ->required(),
                    Forms\Components\Hidden::make('type')
                        ->default('activity'),
                ])
                    ->columns(1)
            )
            ->action(function (array $data, ?Model $record = null) {
                try {
                    $record->addChat($data, Auth::user()->id);

                    Notification::make()
                        ->success()
                        ->title('Message Sent')
                        ->body('Your message has been sent successfully.')
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title('Message Sending Failed')
                        ->body('An error occurred: '.$e->getMessage())
                        ->send();
                }
            })
            ->label('Activity')
            ->icon('heroicon-o-clock')
            ->modalSubmitAction(function ($action) {
                $action->label('Schedule');
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
