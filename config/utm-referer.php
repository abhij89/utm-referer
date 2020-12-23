<?php

return [

    /*
     * The key that will be used to remember the referer in the cookie.
     */
    'referer_cookie_key' => 'user-referer',
    
    /*
     * The key that will be used to remember the utm tags in the cookie.
     */
    'utm_cookie_key' => 'user-utms',

    /*
     * The sources used to determine the referer.
     */
    'sources' => [
        Spatie\Referer\Sources\UTMSource::class,
        Spatie\Referer\Sources\RequestHeader::class,
    ],
];
