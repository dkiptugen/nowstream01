<?php
    return [
        'name'        => env( 'COMPANY NAME','Caydeesoft Solutions' ),
        'title'       => env( 'SITE_TITLE','Streamer' ),
        'tagline'     => env( 'SITE_TAGLINE','Home of online streaming' ),
        'description' => env( 'SITE_DESCRIPTION','The best Streaming platform in town' ),
        'logo'        => env( 'SITE_URL'). 'nowstream.png' ,
        'image'       =>env( 'SITE_URL'). 'assets/favicon/favicon-32x32.png',
        'keywords'    => 'Content,broadcast, kenya, events',
        'author'      => 'Caydeesoft Solution Limited',
        'twitter_handle'=>'',
        'social'      => [
            "facebook" => env( 'SOCIAL_FACEBOOK' ),
            "twitter"  => env( "SOCIAL_TWITTER" ),
            "youtube"  => env( "SOCIAL_YOUTUBE" ),
            "whatsapp" => env( "SOCIAL_WHATSAPP" ),
        ],

    ];
