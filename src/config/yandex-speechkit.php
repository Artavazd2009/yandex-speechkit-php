<?php

return [
    'oauth_token' => env('YANDEX_OAUTH_TOKEN'),

    'api_key' => env('YANDEX_API_KEY'),

    'folder_id' => env('YANDEX_FOLDER_ID'),

    'default_model' => env('YANDEX_SPEECHKIT_MODEL', 'general'),

    'default_languages' => ['ru-RU'],

    'poll_interval_seconds' => env('YANDEX_SPEECHKIT_POLL_INTERVAL', 10),

    'max_wait_seconds' => env('YANDEX_SPEECHKIT_MAX_WAIT', 14400),
];
