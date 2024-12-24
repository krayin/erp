<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Webkul\Security\Models\User;
use Webkul\Support\Models\ActivityPlan;
use Webkul\Support\Models\ActivityType;

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
            ->form(function ($form, $record) {
                return $form->schema([
                    Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\Select::make('activity_plan_id')
                                        ->label(__('Activity Plan'))
                                        ->options($record->activityPlan()->pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->live(),
                                    Forms\Components\DatePicker::make('date_deadline')
                                        ->label('Plan Date')
                                        ->hidden(fn(Get $get) => ! $get('activity_plan_id'))
                                        ->live()
                                        ->native(false)
                                        ->required(),
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
                                                $html .= '<div class="flex items-center space-x-2">
                                                            <span>•</span>
                                                            <span>' . $activityPlanTemplate->summary . ($planDate ? ' (' . $planDate . ')' : '') . '</span>
                                                          </div>';
                                            }
                                            $html .= '</div>';

                                            return new HtmlString($html);
                                        })->hidden(fn(Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\Select::make('activity_type_id')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.activity-type'))
                                        ->options(ActivityType::pluck('name', 'id'))
                                        ->searchable()
                                        ->preload()
                                        ->live()
                                        ->required()
                                        ->visible(fn(Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\DatePicker::make('date_deadline')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.due-date'))
                                        ->native(false)
                                        ->visible(fn(Get $get) => ! $get('activity_plan_id')),
                                    Forms\Components\TextInput::make('summary')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.summary')),
                                    Forms\Components\Select::make('assigned_to')
                                        ->label(__('chatter::app.filament.actions.chatter.activity.form.assigned-to'))
                                        ->searchable()
                                        ->live()
                                        ->visible(fn(Get $get) => ! $get('activity_plan_id'))
                                        ->options(User::all()->pluck('name', 'id')->toArray())
                                        ->required()
                                ])->columns(2),
                            Forms\Components\RichEditor::make('body')
                                ->hiddenLabel()
                                ->visible(fn(Get $get) => ! $get('activity_plan_id'))
                                ->label(__('chatter::app.filament.actions.chatter.activity.form.type-your-message-here'))
                                ->visible(fn(Get $get) => ! $get('activity_plan_id')),
                            Forms\Components\Hidden::make('type')
                                ->default('activity'),
                        ]),
                ]);
            })
            ->action(function (array $data, ?Model $record = null) {
                try {
                    if (isset($data['activity_plan_id'])) {
                        $activityPlanTemplates = ActivityPlan::find($data['activity_plan_id'])
                            ->activityPlanTemplates;

                        foreach ($activityPlanTemplates as $activityPlanTemplate) {
                            $data = [
                                ...$data,
                                ...$activityPlanTemplate->toArray(),
                                'body' => $activityPlanTemplate['note'] ?? null,
                                'causer_type' => Auth::user()?->getMorphClass(),
                                'causer_id' => Auth::id(),
                            ];

                            $record->addMessage($data, Auth::user()->id);
                        }
                    } else {
                        $data = [
                            ...$data,
                            'content' => $activityPlanTemplate['note'] ?? null,
                            'causer_type' => Auth::user()?->getMorphClass(),
                            'causer_id' => Auth::id(),
                        ];

                        $record->addMessage($data, Auth::user()->id);
                    }

                    Notification::make()
                        ->success()
                        ->title(__('chatter::app.filament.actions.chatter.activity.action.notification.success.title'))
                        ->body(__('chatter::app.filament.actions.chatter.activity.action.notification.success.body'))
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::app.filament.actions.chatter.activity.action.notification.danger.title'))
                        ->body(__('chatter::app.filament.actions.chatter.activity.action.notification.danger.body'))
                        ->send();

                    dd($e);
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
