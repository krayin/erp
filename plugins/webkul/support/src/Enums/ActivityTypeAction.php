<?php

namespace Webkul\Support\Enums;

enum ActivityTypeAction: string
{
    case NONE = 'none';

    case UPLOAD_FILE = 'upload_file';

    case DEFAULT = 'default';

    case PHONE_CALL = 'phone_call';

    case MEETING = 'meeting';

    public static function options(): array
    {
        return [
            self::NONE->value        => __('support::enums/activity-type-action.none'),
            self::UPLOAD_FILE->value => __('support::enums/activity-type-action.upload-file'),
            self::DEFAULT->value     => __('support::enums/activity-type-action.default'),
            self::PHONE_CALL->value  => __('support::enums/activity-type-action.phone-call'),
            self::MEETING->value     => __('support::enums/activity-type-action.meeting'),
        ];
    }
}
