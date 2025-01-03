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
            <hr>
            <div>
                <b style="font-size: 11px;">YourCompany</b>
                <p style="color: #999; font-size: 11px;">
                    +1 555-555-5556 |
                    <a href="mailto:info@yourcompany.com" style="color: #999;">info@yourcompany.com</a> |
                    <a href="http://www.example.com" style="color: #999;">www.example.com</a>
                </p>
                <p style="color: #555; font-size: 11px;">
                    Powered by <a href="https://www.krayin.com" target="_blank" style="color: #875A7B;">Krayin</a>
                </p>
            </div>
        </div>
    </body>
</html>
