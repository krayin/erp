<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .breadcrumb {
            font-size: 14px;
            margin-bottom: 20px;
            border-bottom: 1px solid rgb(204, 204, 204);
            padding-bottom: 10px;
        }

        .breadcrumb p {
            font-weight: bold;
            margin: 0;
        }

        .notification {
            margin: 20px 0;
            color: #555;
            font-size: 13px;
        }

        .company-info {
            font-size: 13px;
            color: #666;
            border-top: 1px solid rgb(204, 204, 204);
            padding-top: 10px;
        }

        .company-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .company-details {
            margin: 0;
        }

        .powered-by {
            margin-top: 10px;
            font-size: 12px;
            color: #999;
        }

        .powered-by a {
            color: #666;
            text-decoration: none;
        }

        .powered-by a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="breadcrumb">
        <p>{{ $payload['record_name'] }}</p>
    </div>

    <div class="notification">
        {{ __(':sender_name (:sender_email_address) added you as a follower of this :model_name', [
            'sender_name'  => $payload['from']['name'],
            'sender_email_address' => $payload['from']['address'],
            'model_name'   => $payload['model_name'],
        ]) }}

        @isset($payload['note'])
            <p>{!! $payload['note'] !!}</p>
        @endisset
    </div>

    @isset($payload['from']['company'])
        <div class="company-info">
            <div class="company-name">{{ $payload['from']['company']['name'] }}</div>
            <p class="company-details">
                {{ $payload['from']['company']['phone'] }} | {{ $payload['from']['company']['email'] }} | <a href="{{ $payload['from']['company']['website'] }}">{{ str_replace(['https://', 'http://'], '', $payload['from']['company']['website']) }}</a>
            </p>
        </div>
    @endisset

    <div class="powered-by">
        Powered by <a href="#">{{ config('app.name') }}</a>
    </div>
</body>

</html>
