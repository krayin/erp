<x-mail::message>
# New Message Notification

You have received a new message:

> {!! $content !!}

<x-mail::button :url="$url">
View Message
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
