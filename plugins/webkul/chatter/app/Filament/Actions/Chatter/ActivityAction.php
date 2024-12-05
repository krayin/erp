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
                                ->label(__('chatter::app.filament.actions.chatter.activity.form.activity-type'))
                                ->options(ActivityType::options())
                                ->required(),
                            Forms\Components\DatePicker::make('due_date')
                                ->label(__('chatter::app.filament.actions.chatter.activity.form.due-date'))
                                ->native(false)
                                ->required(),
                        ])->columns(2),
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\TextInput::make('summary')
                                ->label(__('chatter::app.filament.actions.chatter.activity.form.summary'))
                                ->required(),
                            Forms\Components\Select::make('assigned_to')
                                ->label(__('chatter::app.filament.actions.chatter.activity.form.assigned-to'))
                                ->searchable()
                                ->live()
                                ->options(User::all()->pluck('name', 'id')->toArray())
                                ->required(),
                        ])->columns(2),
                    Forms\Components\RichEditor::make('content')
                        ->hiddenLabel()
                        ->label(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
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
                        ->title(__('chatter::app.filament.actions.chatter.activity.action.notification.success.title'))
                        ->body(__('chatter::app.filament.actions.chatter.activity.action.notification.success.body'))
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::app.filament.actions.chatter.activity.action.notification.danger.title'))
                        ->body(__('chatter::app.filament.actions.chatter.activity.action.notification.danger.body'))
                        ->send();

                    report($e);
                }
            })
            ->label(__('chatter::app.filament.actions.chatter.activity.action.label'))
            ->icon('heroicon-o-clock')
            ->modalSubmitAction(function ($action) {
                $action->label(__('chatter::app.filament.actions.chatter.activity.action.modal-submit-action.title'));
                $action->icon('heroicon-m-paper-airplane');
            })
            ->slideOver(false);
    }
}
