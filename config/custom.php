<?php
    return [
        "APP"               => [
            "API_KEY"         => env("API_KEY", "2xKy+zv7qVM6}S/4=sH_"),
            'SITE_URL'        => env('SITE_URL', "https://streamer.co.ke"),
            'ENCRYPTION_KEY'  => env('ENCRYPTION_KEY', 'LJHb1fc6f$2j5FnO7W3@NphwHFmOcMlsg'),
            'ENCRYPTION_SALT' => env('ENCRYPTION_SALT', 'zMjDBmWoUd'),
            'APP_BASE_DOMAIN' => env('APP_BASE_DOMAIN', 'streamer.co.ke'),
            'CLOUDFLARE_API_TOKEN' => env('CLOUDFLARE_API_TOKEN','mGLaLwqDk2oPRgjPdxy13smIp1pTK054mgOYuaNO')
        ], 'AUTHENTICATION' => [
            'PASSWORD_EXPIRY' => env('PASSWORD_EXPIRY', 30),
            'LOGIN_EXPIRY'    => env('LOGIN_EXPIRY', 30),
        ], 'MAIL'           => [
            'MAIL_HOST'         => env('MAIL_HOST'),
            'MAIL_PORT'         => env('MAIL_PORT'),
            'MAIL_USERNAME'     => env('MAIL_USERNAME'),
            'MAIL_PASSWORD'     => env('MAIL_PASSWORD'),
            'MAIL_ENCRYPTION'   => env('MAIL_ENCRYPTION'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_NAME'    => env('MAIL_FROM_NAME'),
            'MAILGUN_API_KEY'   => env('MAILGUN_API_KEY'),
        ], 'SOCIAL_LOGIN'   => [
            'GOOGLE_CLIENT_ID'       => env('GOOGLE_CLIENT_ID'),
            'GOOGLE_CLIENT_SECRET'   => env('GOOGLE_CLIENT_SECRET'),
            'GOOGLE_RETURN_URL'      => env('GOOGLE_RETURN_URL'),
            'FACEBOOK_CLIENT_ID'     => env('FACEBOOK_CLIENT_ID'),
            'FACEBOOK_CLIENT_SECRET' => env('FACEBOOK_CLIENT_SECRET'),
            'FACEBOOK_RETURN_URL'    => env('FACEBOOK_RETURN_URL'),
            'TWITTER_CLIENT_ID'      => env('TWITTER_CLIENT_ID'),
            'TWITTER_CLIENT_SECRET'  => env('TWITTER_CLIENT_SECRET'),
            'TWITTER_RETURN_URL'     => env('TWITTER_RETURN_URL'),
        ],
        "STREAM"            => [
            "LIVESTREAM_SERVER" => env('LIVESTREAM_SERVER', "rtmp://stream.livestreamz.xyz/live"),
            "LIVESTREAM_LINK"   => env('LIVESTREAM_LINK', "https://stream.livestreamz.xyz/hls"),
            "PROXY_TTL_MINUTES" => env('STREAM_PROXY_TTL_MINUTES', 30),
        ], "DATA"           => [
            "DATA_USERNAME"        => env("DATA_USERNAME"),
            "DATA_PASSWORD"        => env("DATA_PASSWORD"),
            "DATA_CONSUMER_KEY"    => env("DATA_CONSUMER_KEY"),
            "DATA_CONSUMER_SECRET" => env("DATA_CONSUMER_SECRET")
        ], "BILLING"        => [
            "RESERVED_CURRENCY" => env("RESERVED_CURRENCY", 'USD')
        ],
        "SMS"               => [
            "AFRICAS_TALKING_USERNAME" => env("AFRICAS_TALKING_USERNAME"),
            "AFRICAS_TALKING_API_KEY"  => env("AFRICAS_TALKING_API_KEY"),
        ], "PUSHER"         => [
            "PUSHER_APP_ID"      => env("PUSHER_APP_ID"),
            "PUSHER_APP_KEY"     => env("PUSHER_APP_KEY"),
            "PUSHER_APP_SECRET"  => env("PUSHER_APP_SECRET"),
            "PUSHER_APP_CLUSTER" => env("PUSHER_APP_CLUSTER"),
        ],
        'TYPESENSE'=>[
            'SCOUT_DRIVER'=>env('SCOUT_DRIVER','typesense'),
            'TYPESENSE_API_KEY' => env('TYPESENSE_API_KEY'),
            'TYPESENSE_HOST' => env('TYPESENSE_HOST','localhost'),
            'TYPESENSE_PORT' => env('TYPESENSE_PORT','8108'),
            'TYPESENSE_PROTOCOL' => env('TYPESENSE_PROTOCOL','http')

        ]

    ];
