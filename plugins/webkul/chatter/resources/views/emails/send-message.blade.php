<x-mail::message>
# {{ __('chatter::views/emails/send-message.new-message-notification') }}

{!! $messageBody !!}

{{ config('app.name') }}

</x-mail::message>
