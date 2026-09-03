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

    'wa_bot' => [
        'url' => env('WA_BOT_URL', 'http://127.0.0.1:3000'),
        'bot_dir' => env('WA_BOT_DIR', base_path('bot')),
        'pm2_app_name' => env('WA_BOT_PM2_APP_NAME', 'wa-bot'),
        'pm2_bin' => env('WA_BOT_PM2_BIN', 'pm2'),
        'pm2_home' => env('WA_BOT_PM2_HOME'),
    ],

];
