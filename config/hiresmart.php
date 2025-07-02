<?php

return [
    'pagination' => [
        'default_per_page' => env('DEFAULT_PER_PAGE', 15),
        'max_per_page' => env('MAX_PER_PAGE', 100),
    ],

    'cache' => [
        'ttl' => [
            'job_listings' => env('CACHE_TTL_JOB_LISTINGS', 300), // 5 minutes
            'application_stats' => env('CACHE_TTL_APPLICATION_STATS', 600), // 10 minutes
        ],
    ],

    'rate_limit' => [
        'login' => [
            'attempts' => env('RATE_LIMIT_LOGIN_ATTEMPTS', 5),
            'decay_minutes' => env('RATE_LIMIT_LOGIN_DECAY_MINUTES', 15),
        ],
        'application_submission' => [
            'attempts' => env('RATE_LIMIT_APPLICATION_SUBMISSIONS', 10),
            'decay_minutes' => env('RATE_LIMIT_APPLICATION_DECAY_MINUTES', 60),
        ],
    ],

    'job' => [
        'archive_after_days' => env('JOB_ARCHIVE_AFTER_DAYS', 30),
    ],

    'user' => [
        'remove_unverified_after_days' => env('REMOVE_UNVERIFIED_AFTER_DAYS', 7),
    ],
];
