<?php

namespace Spatie\Referer\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    public static function emptyCookieKey(): self
    {
        return new self("`utm-referer.referer_cookie_key` or `utm-referer.utm_cookie_key` can't be empty");
    }
}
