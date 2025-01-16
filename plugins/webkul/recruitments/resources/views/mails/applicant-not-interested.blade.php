<x-support::emails.layout>
    <div class="breadcrumb">
        {{ __('recruitments::mails/applicant-not-interested.breadcrumb', [
            'applicant' => $payload['applicant_name']
        ]) }}
    </div>

    <div class="notification">
        <div class="message-content">
            <p>{!! __('recruitments::mails/applicant-not-interested.greeting') !!}</p>
            <p>{!! __('recruitments::mails/applicant-not-interested.thank-you-message', [
                'company' => $payload['from']['company']['name']
            ]) !!}</p>
            <p>{!! __('recruitments::mails/applicant-not-interested.future-endeavors') !!}</p>
            <p>{!! __('recruitments::mails/applicant-not-interested.mistakes-note') !!}</p>
            <p>{!! __('recruitments::mails/applicant-not-interested.resume-on-record') !!}</p>
            <p>{!! __('recruitments::mails/applicant-not-interested.best-regards') !!}</p>
            <div class="admin-details">
                <p><strong>{!! __('recruitments::mails/applicant-not-interested.admin-details.name', [
                    'name' => $payload['from']['name']
                ]) !!}</strong></p>
                <p>{!! __('recruitments::mails/applicant-not-interested.admin-details.email', [
                    'email' => $payload['from']['address']
                ]) !!}</p>
            </div>
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
        font-size: 13px;
        color: #555;
    }

    .separator {
        background-color: rgb(204, 204, 204);
        height: 1px;
        border: none;
        margin: 16px 0;
    }

    .message-content {
        font-size: 13px;
        color: #555;
        line-height: 1.6;
    }

    .admin-details {
        margin-top: 16px;
        font-size: 12px;
        color: #666;
    }

    .admin-details strong {
        font-weight: bold;
    }

    .company-info {
        font-size: 13px;
        color: #666;
        border-top: 1px solid rgb(204, 204, 204);
        padding-top: 10px;
    }

    .company-name {
        font-weight: bold;
    }

    .company-details {
        margin: 0;
    }

    .company-details a {
        color: #0056b3;
        text-decoration: none;
    }

    .company-details a:hover {
        text-decoration: underline;
    }
</style>
