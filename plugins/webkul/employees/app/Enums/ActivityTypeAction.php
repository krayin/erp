<?php

namespace Webkul\Employee\Enums;

enum ActivityTypeAction: string
{
    case NONE = 'none';

    case UPLOAD_FILE = 'upload_file';

    case DEFAULT = 'default';

    case PHONE_CALL = 'phone_call';

    public static function options(): array
    {
        return [
            self::NONE->value        => 'None',
            self::UPLOAD_FILE->value => 'Upload File',
            self::DEFAULT->value     => 'Default',
            self::PHONE_CALL->value  => 'Phone Call',
        ];
    }
}
