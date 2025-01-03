<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notification</title>
    <style>
        hr {
            background-color:rgb(204,204,204);
            border:medium none;
            clear:both;
            display:block;
            font-size:0px;
            min-height:1px;
            line-height:0;
            margin: 10px 0px;
        }
    </style>
</head>
<body style="font-family: Verdana, Arial, sans-serif; color: #454748;">
    <body style="font-family: Verdana, Arial, sans-serif; color: #454748;">
        <div style="max-width: 900px; width: 100%;">
            <div style="font-size: 13px;">
                {!! $body !!}
            </div>

            @if ($sender?->defaultCompany)
                <hr>
                <div>
                    <b style="font-size: 11px;">{{ $sender?->defaultCompany?->name }}</b>
                    <p style="color: #999; font-size: 11px;">
                        {{ $sender?->defaultCompany?->phone }} |
                        <a href="mailto:{{ $sender?->defaultCompany?->email }}" style="color: #999;">{{ $sender?->defaultCompany?->email }}</a> |
                        <a href="http://www.example.com" style="color: #999;">www.example.com</a>
                    </p>
                    <p style="color: #555; font-size: 11px;">
                        Powered by <a href="https://www.krayin.com" target="_blank" style="color: #875A7B;">{{ config('app.name') }}</a>
                    </p>
                </div>
            @endif
        </div>
    </body>
</html>
