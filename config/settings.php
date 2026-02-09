<?php
    return [
        'permissions'       => [
            "channel"              => [
                "create_channel",
                "edit_channel",
                "destroy_channel",
                "view_channel",
                "view_specific_channel"
            ],
            "event"          => [
                "create_event",
                "edit_event",
                "destroy_event",
                "view_event",
                "create_event_rate",
                "edit_event_rate",
                "destroy_event_rate",
                "view_event_rate",
            ],

            "user"              => [
                "create_user",
                "edit_user",
                "destroy_user",
                "view_user",
                "assign_user_role",
                "export_user"
            ],

            "role"              => [
                "create_role",
                "edit_role",
                "destroy_role",
                "view_role",
                "add_permission_role"
            ],
            "permission"        => [
                "create_permission",
                "edit_permission",
                "destroy_permission",
                "view_permission"
            ],
            "subscription"        => [
                "create_subscription",
                "edit_subscription",
                "destroy_subscription",
                "view_subscription"
            ],
            "transaction"        => [
                "create_transaction",
                "edit_transaction",
                "destroy_transaction",
                "view_transaction"
            ],
            "payment_method"    => [
                "create_payment_method",
                "edit_payment_method",
                "destroy_payment_method",
                "view_payment_method",
            ],

            "video"    => [
                "create_video",
                "edit_video",
                "destroy_video",
                "view_video",
            ],
            "stream"    => [
                "create_stream",
                "edit_stream",
                "destroy_stream",
                "view_stream",
            ],
            "podcast"    => [
                "create_podcast",
                "edit_podcast",
                "destroy_podcast",
                "view_podcast",
            ],
            "radio"    => [
                "create_radio",
                "edit_radio",
                "destroy_radio",
                "view_radio",
            ],
            "tv"    => [
                "create_tv",
                "edit_tv",
                "destroy_tv",
                "view_tv",
            ],
            "category"    => [
                "create_category",
                "edit_category",
                "destroy_category",
                "view_category",
            ],
        ]
    ];
