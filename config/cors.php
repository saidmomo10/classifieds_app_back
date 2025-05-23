<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => [
        'api/*',
        'broadcasting/auth',
        'login/google/callback',
        'login',
        'logout',
        'register',
        // 'user/password', 
        // 'forgot-password',
        // 'reset-password',
        'sanctum/csrf-cookie',
        // 'user/profile-information',
        // 'email/verification-notification',
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['https://kaolo-annonce.vercel.app/', 'http://localhost:5173'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
