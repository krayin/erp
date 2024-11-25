<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Reset styles */
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f4f6f8;
            color: #1a1a1a;
            line-height: 1.6;
        }

        /* Main container */
        .wrapper {
            width: 100%;
            background-color: #f4f6f8;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        /* Header section */
        .header {
            background-color: #7c3aed;
            padding: 32px 40px;
            text-align: center;
        }

        .header-logo {
            margin-bottom: 24px;
        }

        .greeting {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            padding: 0;
        }

        /* Content section */
        .content-wrapper {
            padding: 40px;
            background-color: #ffffff;
        }

        .message-box {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
            border-left: 4px solid #7c3aed;
        }

        .message-meta {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            color: #6b7280;
            font-size: 14px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 12px;
            background-color: #7c3aed;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .message-content {
            color: #374151;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Action button */
        .action-button {
            display: inline-block;
            background-color: #7c3aed;
            color: #ffffff;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            margin: 24px 0;
            text-align: center;
        }

        /* Footer section */
        .footer {
            background-color: #f9fafb;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            color: #6b7280;
            font-size: 14px;
            margin: 0;
        }

        .social-links {
            margin: 20px 0;
        }

        .social-link {
            display: inline-block;
            margin: 0 8px;
            color: #7c3aed;
            text-decoration: none;
            font-weight: 500;
        }

        .divider {
            height: 1px;
            background-color: #e5e7eb;
            margin: 20px 0;
        }

        /* Responsive design */
        @media only screen and (max-width: 600px) {
            .wrapper {
                padding: 20px 0;
            }

            .container {
                border-radius: 12px;
            }

            .header, .content-wrapper, .footer {
                padding: 20px;
            }

            .greeting {
                font-size: 20px;
            }

            .message-box {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header Section -->
            <div class="header">
                <div class="header-logo">
                    <img src="/api/placeholder/120/40" alt="Company Logo" />
                </div>
                <h1 class="greeting">
                    Hello, {{ $follower->name }} 👋
                </h1>
            </div>

            <!-- Content Section -->
            <div class="content-wrapper">
                <div class="message-box">
                    <div class="message-meta">
                        <div class="avatar">
                            {{ substr($follower->name, 0, 1) }}
                        </div>
                        <span>New message from {{ $record->user->name }}</span>
                    </div>
                    <div class="message-content">
                        {!! $content !!}
                    </div>
                </div>

                <a href="#" class="action-button">
                    View Message
                </a>
            </div>

            <!-- Footer Section -->
            <div class="footer">
                <div class="social-links">
                    <a href="#" class="social-link">Twitter</a>
                    <a href="#" class="social-link">LinkedIn</a>
                    <a href="#" class="social-link">Facebook</a>
                </div>

                <div class="divider"></div>

                <p class="footer-text">
                    © 2024 Your Company. All rights reserved.
                    <br><br>
                    123 Company Street, City, Country
                </p>

                <div style="margin-top: 16px; font-size: 12px; color: #9ca3af;">
                    <a href="#" style="color: #7c3aed; text-decoration: none;">Unsubscribe</a> •
                    <a href="#" style="color: #7c3aed; text-decoration: none;">Privacy Policy</a> •
                    <a href="#" style="color: #7c3aed; text-decoration: none;">Terms of Service</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
