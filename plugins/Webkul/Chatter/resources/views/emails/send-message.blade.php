<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 640px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .content {
            color: #333;
            font-size: 16px;
            line-height: 1.6;
        }
        .header {
            font-weight: 600;
            font-size: 24px;
            color: #121A26;
            line-height: 32px;
            margin-bottom: 24px;
            text-align: center;
        }
        .footer {
            font-size: 14px;
            color: #999;
            text-align: center;
            margin-top: 40px;
            line-height: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Hello, {{ $follower->name }}, 👋
        </div>
        <div class="content">
            {!! $content !!}
        </div>
        <div class="footer">
            © 2024 Your Company Name. All rights reserved.
        </div>
    </div>
</body>
</html>
