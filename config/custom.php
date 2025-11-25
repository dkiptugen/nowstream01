<?php
	return [
		"APP"                  => [
			"API_KEY"         => env("API_KEY", "2xKy+zv7qVM6}S/4=sH_"),
			'SITE_URL'        => env('SITE_URL', "https://live.baze.co.ke"),
			'ENCRYPTION_KEY'  => env('ENCRYPTION_KEY', 'LJHb1fc6f$2j5FnO7W3@NphwHFmOcMlsg'),
			'ENCRYPTION_SALT' => env('ENCRYPTION_SALT', 'zMjDBmWoUd')
		], 'AUTHENTICATION'    => [
			'PASSWORD_EXPIRY' => env('PASSWORD_EXPIRY', 30),
			'LOGIN_EXPIRY' => env('LOGIN_EXPIRY', 30),
		], 'MAIL'              => [
			'MAIL_HOST'         => env('MAIL_HOST'),
			'MAIL_PORT'         => env('MAIL_PORT'),
			'MAIL_USERNAME'     => env('MAIL_USERNAME'),
			'MAIL_PASSWORD'     => env('MAIL_PASSWORD'),
			'MAIL_ENCRYPTION'   => env('MAIL_ENCRYPTION'),
			'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
			'MAIL_FROM_NAME'    => env('MAIL_FROM_NAME'),
			'MAILGUN_API_KEY'   => env('MAILGUN_API_KEY'),
		], 'SOCIAL_LOGIN'      => [
			'GOOGLE_CLIENT_ID'       => env('GOOGLE_CLIENT_ID'),
			'GOOGLE_CLIENT_SECRET'   => env('GOOGLE_CLIENT_SECRET'),
			'GOOGLE_RETURN_URL'      => env('GOOGLE_RETURN_URL'),
			'FACEBOOK_CLIENT_ID'     => env('FACEBOOK_CLIENT_ID'),
			'FACEBOOK_CLIENT_SECRET' => env('FACEBOOK_CLIENT_SECRET'),
			'FACEBOOK_RETURN_URL'    => env('FACEBOOK_RETURN_URL'),
			'TWITTER_CLIENT_ID'      => env('TWITTER_CLIENT_ID'),
			'TWITTER_CLIENT_SECRET'  => env('TWITTER_CLIENT_SECRET'),
			'TWITTER_RETURN_URL'     => env('TWITTER_RETURN_URL'),
		], 'SAFARICOM_CONTENT' => [
			"SAFARICOM_CONTENT_USERNAME"      => env("SAFARICOM_CONTENT_USERNAME"),
			"SAFARICOM_CONTENT_PASSWORD"      => env("SAFARICOM_CONTENT_PASSWORD"),
			"SAFARICOM_CONTENT_TOKEN_URL"     => env("SAFARICOM_CONTENT_TOKEN_URL"),
			'SAFARICOM_CONTENT_QUERY_SUB_URL' => env('SAFARICOM_CONTENT_QUERY_SUB_URL'),
			'SAFARICOM_CONTENT_CSPID'         => env('SAFARICOM_CONTENT_CSPID')
		], 'MPESA'             => [
			'MPESA_CONSUMER_KEY'               => env('MPESA_CONSUMER_KEY'),
			'MPESA_SHORTCODE'                  => env('MPESA_SHORTCODE'),
			'MPESA_CONSUMER_SECRET'            => env('MPESA_CONSUMER_SECRET'),
			'MPESA_PASS_KEY'                   => env('MPESA_PASS_KEY'),
			'MPESA_WHITELISTED_IP'             => env('MPESA_WHITELISTED_IP', '127.0.0.1'),
			'MPESA_BLACKLISTED_IP'             => env('MPESA_BLACKLISTED_IP'),
			'MPESA_BLACKLISTED_IP_ACTION'      => env('MPESA_BLACKLISTED_IP_ACTION', 'block'),
			'MPESA_ANY_OTHER_IP_ACTION'        => env('MPESA_ANY_OTHER_IP_ACTION', 'notify'),
			'MPESA_PAYMENT_NOTIFICATION_EMAIL' => env('MPESA_PAYMENT_NOTIFICATION_EMAIL'),
		], 'DPO'               => [
			'DPO_COMPANY_TOKEN'   => env('DPO_COMPANY_TOKEN'),
			'DPO_SERVICE_CODE' => env('DPO_SERVICE_CODE'),
			'DPO_DEFAULT_CHANNEL' => env('DPO_DEFAULT_CHANNEL'),
			'DPO_BLOCK_CHANNELS' => env('DPO_BLOCK_CHANNELS'),
			'DPO_PAYMENT_URL'     => env('DPO_PAYMENT_URL', 'https://secure.3gdirectpay.com/payv3.php?ID=')
		], "STREAM"            => [
			"LIVESTREAM_SERVER" => env('LIVESTREAM_SERVER', "rtmp://stream.livestreamz.xyz/live"),
			"LIVESTREAM_LINK"   => env('LIVESTREAM_LINK', "https://stream.livestreamz.xyz/hls"),
		], "DATA"              => [
			"DATA_USERNAME"     => env("DATA_USERNAME"),
			"DATA_PASSWORD" => env("DATA_PASSWORD"),
			"DATA_CONSUMER_KEY" => env("DATA_CONSUMER_KEY"),
			"DATA_CONSUMER_SECRET" => env("DATA_CONSUMER_SECRET")
		], "BILLING"           => [
			"RESERVED_CURRENCY" => env("RESERVED_CURRENCY",'USD')
		], "SMS"               => [
			"AFRICAS_TALKING_USERNAME" => env("AFRICAS_TALKING_USERNAME"),
			"AFRICAS_TALKING_API_KEY"  => env("AFRICAS_TALKING_API_KEY"),
		], "PUSHER"               => [
			"PUSHER_APP_ID"  => env("PUSHER_APP_ID"), 
			"PUSHER_APP_KEY" => env("PUSHER_APP_KEY"),
			"PUSHER_APP_SECRET"  => env("PUSHER_APP_SECRET"), 
			"PUSHER_APP_CLUSTER"  => env("PUSHER_APP_CLUSTER"), 
		]
	
	];
