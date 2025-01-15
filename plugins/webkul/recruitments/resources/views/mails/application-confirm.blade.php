<x-support::emails.layout>
    <div class="breadcrumb">
        Your Applicant <p>{{ $payload['record_name']  }}</p>
    </div>

    <div class="notification">
        <p>Hello,</p>
        <p>We confirm we successfully received your application for the job
            "<strong class="highlighted-text">{{ $payload['job_position'] }}</strong>" @isset($payload['from']['company']) at <strong>{{ $payload['from']['company']['name'] }}</strong>@endisset.
        </p>
        <p>We will come back to you shortly.</p>
        <hr class="separator">
        <h3 class="next-step-title">What is the next step?</h3>
        <p>We usually <strong>answer applications within a few days.</strong></p>
        <p>Feel free to <strong>contact us if you want faster feedback</strong> or if you don't get news from us quickly enough (just reply to this email).</p>
    </div>

    @isset($payload['from']['company'])
        <div class="company-info">
            <div class="company-name">{{ $payload['from']['company']['name'] }}</div>
            <p class="company-details">
                {{ $payload['from']['company']['phone'] }} | {{ $payload['from']['company']['email'] }} | <a href="{{ $payload['from']['company']['website'] }}">{{ str_replace(['https://', 'http://'], '', $payload['from']['company']['website']) }}</a>
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
        color: #9A6C8E;
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
        color: #9A6C8E;
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
