<x-support::emails.layout>
    <div class="breadcrumb">
        {{ __('recruitments::mails/internal-communication.breadcrumb', [
            'applicant' => $payload['record_name']
        ]) }}
    </div>

    <div class="notification">
        <p>{{ __('recruitments::mails/internal-communication.greeting', [
            'interviewer' => $payload['to']['name']
        ]) }}</p>
        <p>{{ __('recruitments::mails/internal-communication.assignment_message') }} <strong>{{ $payload['record_name'] }}</strong>.</p>
        <hr class="separator">
        <p class="internal-note">
            <strong>{{ __('recruitments::mails/internal-communication.internal_communication') }}</strong> {{ __('recruitments::mails/internal-communication.internal_note') }}
        </p>
        <div class="view-button-container">
            <a href="{{ $payload['record_url'] }}" class="view-button">
                {{ __('recruitments::mails/internal-communication.view_applicant') }}
            </a>
        </div>
    </div>

    @isset($payload['from']['company'])
        <div class="company-info">
            <div class="company-name">{{ $payload['from']['company']['name'] }}</div>
            <p class="company-details">
                {{ $payload['from']['company']['phone'] }} | {{ $payload['from']['company']['email'] }} |
                <a href="{{ $payload['from']['company']['website'] }}">
                    {{ str_replace(['https://', 'http://'], '', $payload['from']['company']['website']) }}
                </a>
            </p>
        </div>
    @endisset
</x-support::emails.layout>

<style>
    .breadcrumb {
        font-size: 14px;
        margin-bottom: 20px;
        border-bottom: 1px solid rgb(204, 204, 204);
        padding-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .breadcrumb p {
        font-weight: bold;
        margin: 0;
        margin-left: 10px;
    }

    .notification {
        margin: 15px 0;
        color: #555;
        font-size: 13px;
    }

    .internal-note {
        background-color: #4394eb;
        padding: 5px;
        color: #ffffff;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .view-button-container {
        margin-top: 10px;
    }

    .view-button {
        display: inline-block;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: bold;
        color: #ffffff;
        background-color: #007bff;
        border-radius: 3px;
        text-decoration: none;
    }

    .view-button:hover {
        background-color: #007bff;
    }

    .separator {
        background-color: rgb(204, 204, 204);
        border: none;
        display: block;
        font-size: 0px;
        height: 1px;
        margin: 16px 0;
    }

    .company-info {
        font-size: 13px;
        color: #666;
        border-top: 1px solid rgb(204, 204, 204);
        padding-top: 10px;
    }

    .company-details {
        margin: 0;
    }

    .company-contact {
        text-decoration: none;
        color: #999999;
    }

    .footer {
        font-size: 11px;
        color: #555555;
        margin-top: 16px;
    }

    .footer-text {
        margin: 0;
    }

    .footer-link {
        color: #875A7B;
        text-decoration: none;
    }

    .footer-link:hover {
        text-decoration: underline;
    }
</style>
