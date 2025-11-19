<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'ml' => [
        'base_url' => env('ML_SERVICE_BASE_URL', 'http://127.0.0.1:8001'),
        'predict_endpoint' => env('ML_SERVICE_PREDICT_ENDPOINT', '/api/v1/predict'),
        'api_key' => env('ML_SERVICE_API_KEY'),
        'timeout' => (int) env('ML_SERVICE_TIMEOUT', 10),
        'retry_attempts' => (int) env('ML_SERVICE_RETRY_ATTEMPTS', 3),
        'retry_delay' => (int) env('ML_SERVICE_RETRY_DELAY', 250),
    ],

];
