<?php

return [
    'mailgun'   => ['domain' => env('MAILGUN_DOMAIN'), 'secret' => env('MAILGUN_SECRET'), 'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net')],
    'postmark'  => ['token' => env('POSTMARK_TOKEN')],
    'ses'       => ['key' => env('AWS_ACCESS_KEY_ID'), 'secret' => env('AWS_SECRET_ACCESS_KEY'), 'region' => env('AWS_DEFAULT_REGION', 'us-east-1')],

    'claude' => [
        'api_key'    => env('CLAUDE_API_KEY'),
        'model'      => env('CLAUDE_MODEL', 'claude-sonnet-4-6'),
        'max_tokens' => env('CLAUDE_MAX_TOKENS', 4096),
    ],

    'onvio' => [
        'base_url'  => env('ONVIO_BASE_URL'),
        'api_key'   => env('ONVIO_API_KEY'),
        'client_id' => env('ONVIO_CLIENT_ID'),
    ],
];
