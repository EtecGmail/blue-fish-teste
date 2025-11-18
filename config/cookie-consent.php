<?php

return [
    'enabled' => env('COOKIE_CONSENT_ENABLED', true),

    'cookie_name' => env('COOKIE_CONSENT_NAME', 'bluefish_cookie_consent'),

    'cookie_lifetime' => env('COOKIE_CONSENT_LIFETIME', 365 * 2),
];
