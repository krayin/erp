<?php

namespace Webkul\Chatter\Livewire;

use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\WithFileUploads;
use Webkul\Chatter\Filament\Actions\Chatter\ActivityAction;
use Webkul\Chatter\Filament\Actions\Chatter\FileAction;
use Webkul\Chatter\Filament\Actions\Chatter\FollowerAction;
use Webkul\Chatter\Filament\Actions\Chatter\LogAction;
use Webkul\Chatter\Filament\Actions\Chatter\MessageAction;
use Webkul\Chatter\Filament\Infolists\Components\Activities\ActivitiesRepeatableEntry;
use Webkul\Chatter\Filament\Infolists\Components\Activities\ContentTextEntry as ActivityContentTextEntry;
use Webkul\Chatter\Filament\Infolists\Components\Activities\TitleTextEntry as ActivityTitleTextEntry;
use Webkul\Chatter\Filament\Infolists\Components\Messages\ContentTextEntry as MessageContentTextEntry;
use Webkul\Chatter\Filament\Infolists\Components\Messages\MessageRepeatableEntry;
use Webkul\Chatter\Filament\Infolists\Components\Messages\TitleTextEntry as MessageTitleTextEntry;
use Webkul\Chatter\Models\Message;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\ActivityPlan;
use Webkul\Support\Models\ActivityType;

class ChatterPanel extends Component implements HasActions, HasForms, HasInfolists
{
    use InteractsWithActions, InteractsWithForms, InteractsWithInfolists, WithFileUploads;

    public Model $record;

    public mixed $activityPlans;

    public function mount(Model $record, mixed $activityPlans): void
    {
        $this->record = $record;

        $this->activityPlans = $activityPlans;
    }

    public function messageAction(): MessageAction
    {
        return MessageAction::make('message')
            ->record($this->record);
    }

    public function logAction(): LogAction
    {
        return LogAction::make('log')
            ->record($this->record);
    }

    public function fileAction(): FileAction
    {
        return FileAction::make('file')
            ->hiddenLabel()
            ->record($this->record);
    }

    public function followerAction(): FollowerAction
    {
        return FollowerAction::make('follower')
            ->hiddenLabel()
            ->record($this->record);
    }

    public function activityAction(): ActivityAction
    {
        return ActivityAction::make('activity')
            ->setActivityPlans($this->activityPlans)
            ->record($this->record);
    }

    public function removeFollower($partnerId)
    {
        $partner = Partner::findOrFail($partnerId);

        $this->record->removeFollower($partner);
    }

    public function markAsDoneAction(): Action
    {
        return Action::make('markAsDone')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->modalIcon('heroicon-o-check-circle')
            ->form(fn (Form $form) => $form->schema([
                TextInput::make('feedback')
                    ->label('Feedback'),
                Hidden::make('type'),
            ]))
            ->modalFooterActions(fn ($livewire, $arguments): array => [
                Action::make('doneAndScheduleNext')
                    ->icon('heroicon-o-arrow-uturn-right')
                    ->label('Done & Schedule Next')
                    ->modalIcon('heroicon-o-arrow-uturn-right')
                    ->action(function () use ($livewire, $arguments) {
                        $this->processMessage($arguments['id'], $livewire->mountedActionsData[0]['feedback'] ?? null);

                        $this->replaceMountedAction('activity');

                        Notification::make()
                            ->success()
                            ->title('Successfully mark as done.')
                            ->send();
                    })
                    ->cancelParentActions(),
                Action::make('done')
                    ->icon('heroicon-o-check-circle')
                    ->label('Done')
                    ->modalIcon('heroicon-o-check-circle')
                    ->action(function () use ($livewire, $arguments) {
                        $this->processMessage($arguments['id'], $livewire->mountedActionsData[0]['feedback'] ?? null);

                        Notification::make()
                            ->success()
                            ->title('Successfully mark as done.')
                            ->send();
                    })
                    ->cancelParentActions(),
            ]);
    }

    /**
     * Process the message, add a comment, and delete the message.
     */
    protected function processMessage(int $messageId, ?string $feedback): void
    {
        $message = Message::find($messageId);

        if (! $message) {
            return;
        }

        $this->record->addMessage([
            'type' => 'comment',
            'body' => collect([
                $message->activityType?->name ? $message->activityType?->name.' done' : null,
                $message->summary ? ': '.$message->summary : null,
                $message->body ? '<br><div><span class="font-bold">Original Note</span>: '.$message->body.'</div>' : null,
                $feedback ? '<div><span class="font-bold">Feedback</span>: <p>'.$feedback.'</p></div>' : null,
            ])->filter()->implode(''),
        ]);

        $message->delete();
    }

    public function editActivityAction(): Action
    {
        return Action::make('editActivity')
            ->icon('heroicon-o-pencil-square')
            ->modalIcon('heroicon-o-pencil-square')
            ->color('primary')
            ->mountUsing(function (Forms\Form $form, $livewire) {
                $activityId = $livewire->mountedActionsArguments[0]['id'];

                $record = Message::find($activityId);

                $form->fill([
                    'activity_plan_id' => $record->activity_plan_id,
                    'activity_type_id' => $record->activity_type_id,
                    'date_deadline'    => $record->date_deadline,
                    'summary'          => $record->summary,
                    'assigned_to'      => $record->assigned_to,
                    'body'             => $record->body,
                    'type'             => $record->type ?? 'activity',
                ]);
            })
            ->form(function (Forms\Form $form) {
                return $form->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Select::make('activity_plan_id')
                                        ->label(__('Activity Plan'))
                                        ->options($this->activityPlans)
                                        ->searchable()
                                        ->preload()
                                        ->live(),
                                    Forms\Components\DatePicker::make('date_deadline')
                                        ->label('Plan Date')
                                        ->hidden(fn (Get $get) => ! $get('activity_plan_id'))
                                        ->live()
                                        ->native(false),
                                ])
                                ->columns(2),

                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('plan_summary')
                                        ->content(function (Get $get) {
                                            if (! $get('activity_plan_id')) {
                                                return null;
                                            }

                                            $activityPlanTemplates = ActivityPlan::find($get('activity_plan_id'))
                                                ->activityPlanTemplates;

                                            $html = '<div class="space-y-2">';
                                            foreach ($activityPlanTemplates as $activityPlanTemplate) {
                                                $planDate = $get('date_deadline') ? Carbon::parse($get('date_deadline'))->format('m/d/Y') : '';
                                                $html .= '<div class="flex items-center space-x-2" style="margin-left: 20px;">
                                                            <span>•</span>
                                                            <span style="margin-left:2px;">'.$activityPlanTemplate->summary.($planDate ? ' ('.$planDate.')' : '').'</span>
                                                          </div>';
                                            }
                                            $html .= '</div>';

                                            return new HtmlString($html);
                                        })->hidden(fn (Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\Select::make('activity_type_id')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.activity-type'))
                                        ->options(ActivityType::pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->required()
                                        ->visible(fn (Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\DatePicker::make('date_deadline')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.due-date'))
                                        ->native(false)
                                        ->hidden(fn (Get $get) => $get('activity_type_id') ? ActivityType::find($get('activity_type_id'))->category == 'meeting' : false)
                                        ->visible(fn (Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\TextInput::make('summary')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.summary'))
                                        ->visible(fn (Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\Select::make('assigned_to')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.assigned-to'))
                                        ->searchable()
                                        ->hidden(fn (Get $get) => $get('activity_type_id') ? ActivityType::find($get('activity_type_id'))->category == 'meeting' : false)
                                        ->live()
                                        ->visible(fn (Get $get) => ! $get('activity_plan_id'))
                                        ->options(User::all()->pluck('name', 'id')->toArray())
                                        ->required(),
                                ])->columns(2),
                            Forms\Components\RichEditor::make('body')
                                ->hiddenLabel()
                                ->hidden(fn (Get $get) => $get('activity_type_id') ? ActivityType::find($get('activity_type_id'))->category == 'meeting' : false)
                                ->visible(fn (Get $get) => ! $get('activity_plan_id'))
                                ->label(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                                ->visible(fn (Get $get) => ! $get('activity_plan_id')),
                            Forms\Components\Hidden::make('type')
                                ->default('activity'),
                        ]),
                ]);
            })
            ->action(function (array $data, $livewire) {
                $activityId = $livewire->mountedActionsArguments[0]['id'];

                $record = Message::find($activityId);

                $record->update($data);

                Notification::make()
                    ->success()
                    ->title('Activity updated successfully')
                    ->send();
            });
    }

    public function deleteMessageAction(): Action
    {
        return Action::make('deleteMessage')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => $this->record->removeMessage($arguments['id']));
    }

    public function cancelActivityAction(): Action
    {
        return Action::make('cancelActivity')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => $this->record->removeMessage($arguments['id'], 'activities'));
    }

    public function deleteAttachmentAction(): Action
    {
        return Action::make('deleteAttachment')
            ->requiresConfirmation()
            ->action(fn (array $arguments) => dd($arguments));
    }

    public function chatInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema([
                MessageRepeatableEntry::make('messages')
                    ->hiddenLabel()
                    ->schema([
                        MessageTitleTextEntry::make('user')
                            ->hiddenLabel(),
                        MessageContentTextEntry::make('content')
                            ->hiddenLabel(),
                    ])
                    ->placeholder(__('chatter::app.livewire.chatter_panel.placeholders.no_record_found')),
            ]);
    }

    public function activityInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->record)
            ->schema(function () {

                if ($this->record->activities->isEmpty()) {
                    return [];
                }

                return [
                    Section::make('Activities')
                        ->collapsible()
                        ->compact()
                        ->schema([
                            ActivitiesRepeatableEntry::make('activities')
                                ->hiddenLabel()
                                ->schema([
                                    ActivityTitleTextEntry::make('user')
                                        ->hiddenLabel(),
                                    ActivityContentTextEntry::make('content')
                                        ->hiddenLabel(),
                                ])
                                ->placeholder(__('chatter::app.livewire.chatter_panel.placeholders.no_record_found')),
                        ]),
                ];
            });
    }

    public function placeholder()
    {
        return <<<'HTML'
            <div class="flex w-full items-center justify-center">
                <div class="flex flex-col items-center space-y-4">
                    <x-filament::loading-indicator class="text-primary-500 h-10 w-10 animate-spin" />
                    <p class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-300">
                        {{ __('chatter::app.livewire.chatter_panel.placeholders.loading') }}
                    </p>
                </div>
            </div>
        HTML;
    }

    public function render(): View
    {
        return view('chatter::livewire.chatter-panel');
    }
}
