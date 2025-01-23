<x-support::emails.layout>
    <div class="breadcrumb">
        {{ __('recruitments::mails/application-confirm.breadcrumb') }} <p>{{ $payload['record_name'] }}</p>
    </div>

    <div class="notification">
        <p>{{ __('recruitments::mails/application-confirm.hello') }}</p>
        <p>
            {!! __('recruitments::mails/application-confirm.application_received', [
                'job_position' => "<strong class='highlighted-text'>{$payload['job_position']}</strong>",
                'company' => isset($payload['from']['company']) ? "at <strong>{$payload['from']['company']['name']}</strong>" : ''
            ]) !!}
        </p>
        <p>{{ __('recruitments::mails/application-confirm.response_soon') }}</p>
        <hr class="separator">
        <h3 class="next-step-title">{{ __('recruitments::mails/application-confirm.next_step_title') }}</h3>
        <p>{!! __('recruitments::mails/application-confirm.next_step_content') !!}</p>
        <p>{!! __('recruitments::mails/application-confirm.contact_us') !!}</p>
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

    .view-button {
        display: inline-block;
        padding: 5px 10px;
        font-size: 12px;
        font-weight: bold;
        color: #fff;
        background-color: #007bff;
        border-radius: 3px;
        text-decoration: none;
    }

    .view-button:hover {
        background-color: #0056b3;
    }

    .notification {
        margin: 15px 0;
        color: #555;
        font-size: 13px;
    }

    .highlighted-text {
        color: #0056b3;
    }

    .separator {
        background-color: rgb(204, 204, 204);
        border: none;
        display: block;
        font-size: 0px;
        height: 1px;
        line-height: 0;
        margin: 16px 0;
    }

    .next-step-title {
        color: #0056b3;
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
</style>
