<?php

namespace Webkul\Chatter\Filament\Actions\Chatter;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Model;
use Webkul\Chatter\Mail\FollowerMail;
use Webkul\Partner\Models\Partner;
use Webkul\Support\Services\EmailService;

class FollowerAction extends Action
{
    protected string $mailView = 'chatter::mail.follower-mail';

    protected string $resource = '';

    public static function getDefaultName(): ?string
    {
        return 'add.followers.action';
    }

    public function setResource(string $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function setMailView(string $mailView): self
    {
        $this->mailView = $mailView;

        return $this;
    }

    public function getMailView(): string
    {
        return $this->mailView;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-s-user')
            ->color('gray')
            ->modal()
            ->tooltip(__('chatter::filament/resources/actions/chatter/follower-action.setup.tooltip'))
            ->modalIcon('heroicon-s-user-plus')
            ->badge(fn(Model $record): int => $record->followers->count())
            ->modalWidth(MaxWidth::TwoExtraLarge)
            ->slideOver(false)
            ->form(function (Form $form) {
                return $form
                    ->schema([
                        Forms\Components\Select::make('partner_id')
                            ->label(__('chatter::filament/resources/actions/chatter/follower-action.setup.form.fields.recipients'))
                            ->searchable()
                            ->preload()
                            ->searchable()
                            ->relationship('followable', 'name')
                            ->required(),
                        Forms\Components\Toggle::make('notify')
                            ->live()
                            ->label(__('chatter::filament/resources/actions/chatter/follower-action.setup.form.fields.notify-user')),
                        Forms\Components\RichEditor::make('note')
                            ->disableGrammarly()
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'bulletList',
                                'h2',
                                'h3',
                                'italic',
                                'link',
                                'orderedList',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ])
                            ->visible(fn(Get $get) => $get('notify'))
                            ->hiddenLabel()
                            ->placeholder(__('chatter::filament/resources/actions/chatter/follower-action.setup.form.fields.add-a-note')),
                    ])
                    ->columns(1);
            })
            ->modalContentFooter(function (Model $record) {
                return view('chatter::filament.actions.follower-action', [
                    'record' => $record,
                ]);
            })
            ->action(function (Model $record, array $data) {
                $partner = Partner::findOrFail($data['partner_id']);

                try {
                    $record->addFollower($partner);

                    if (
                        !empty($data['notify'])
                        && $data['notify']
                        && $partner
                    ) {

                        app(EmailService::class)->send(
                            view: $this->getMailView(),
                            mailClass: FollowerMail::class,
                            payload: [
                                'record_url'     => $this->prepareResourceUrl($record) ?? '',
                                'record_name'    => $record->name,
                                'model_name'     => $modelName = class_basename($record),
                                'subject'        => __('chatter::filament/resources/actions/chatter/follower-action.setup.actions.mail.subject', [
                                    'model'      => $modelName,
                                    'department' => $record->name,
                                ]),
                                'note'           => $data['note'] ?? '',
                                'to' => [
                                    'address' => $partner->email,
                                    'name'    => $partner->name,
                                ],
                            ],
                        );
                    }

                    Notification::make()
                        ->success()
                        ->title(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.success.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.success.body', ['partner' => $partner?->name]))
                        ->send();
                } catch (\Exception $e) {
                    Notification::make()
                        ->danger()
                        ->title(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.error.title'))
                        ->body(__('chatter::filament/resources/actions/chatter/follower-action.setup.actions.notification.error.body', ['partner' => $partner?->name]))
                        ->send();
                }
            })
            ->hiddenLabel()
            ->modalHeading(__('chatter::filament/resources/actions/chatter/follower-action.setup.title'))
            ->modalSubmitAction(
                fn($action) => $action
                    ->label(__('chatter::filament/resources/actions/chatter/follower-action.setup.submit-action-title'))
                    ->icon('heroicon-m-user-plus')
            );
    }

    private function prepareResourceUrl(mixed $record): string
    {
        return $this->getResource()::getUrl('view', ['record' => $record]);
    }
}
