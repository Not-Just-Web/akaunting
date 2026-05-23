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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'sendgrid' => [
        'api_key' => env('SENDGRID_API_KEY'),
    ],

    'connectips' => [
        'base_url' => env('CONNECTIPS_BASEURL'),
        'merchant_id' => env('CONNECTIPS_MERCHANTID'),
        'app_id' => env('CONNECTIPS_APPID'),
        'app_name' => env('CONNECTIPS_APPNAME', 'Akaunting'),
        'username' => env('CONNECTIPS_USERNAME'),
        'password' => env('CONNECTIPS_PASSWORD'),
        'private_key_path' => env('CONNECTIPS_PRIVATE_KEY_PATH', 'connectips/private_key.pem'),
        'certificate_path' => env('CONNECTIPS_CERTIFICATE_PATH', 'connectips/certificate.pem'),
        'default_currency' => env('CONNECTIPS_DEFAULT_CURRENCY', 'NPR'),
    ],

    'basiq' => [
        'base_url' => env('BASIQ_BASE_URL', 'https://au-api.basiq.io'),
        'auth_url' => env('BASIQ_AUTH_URL', 'https://consent.basiq.io/home'),
        'token_url' => env('BASIQ_TOKEN_URL', 'https://au-api.basiq.io/token'),
        'client_id' => env('BASIQ_CLIENT_ID'),
        'client_secret' => env('BASIQ_CLIENT_SECRET'),
        'scope' => env('BASIQ_SCOPE', 'openid'),
        'redirect_uri' => env('BASIQ_REDIRECT_URI'),
        'statements_path' => env('BASIQ_STATEMENTS_PATH', '/users/me/transactions'),
    ],

];
