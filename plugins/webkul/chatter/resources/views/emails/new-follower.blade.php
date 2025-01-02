<x-mail::message>
# {{ __('chatter::views/emails/new-message.new-follower-notification') }}


{!! __('chatter::views/emails/new-message.hello', ['name' => $followable->name]) !!},

{!! __('chatter::views/emails/new-message.started-followed', [
    'name' => $followerName,
    'app' => config('app.name')
]) !!},

@if(!empty($note))
{!! __('chatter::views/emails/new-message.note', ['note' => $note]) !!}
@endif

{{ __('chatter::views/emails/new-message.thanks') }},<br>
{{ config('app.name') }}
</x-mail::message>
