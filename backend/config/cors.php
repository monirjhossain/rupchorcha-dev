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

    // Apply CORS to API and public auth endpoints used by SPA
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'register',
        'send-otp',
        'verify-otp',
        'auth/*',
        'forgot-password',
        'reset-password',
        'profile',
    ],

    'allowed_methods' => ['*'],

    // Allow both localhost and 127.0.0.1 variations
    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:3001',
        'http://127.0.0.1:3001',
        'http://localhost:8000',
        'http://127.0.0.1:8000',
        'http://localhost:8080',
        'http://127.0.0.1:8080',
        // Add production domain here when deploying
        // 'https://yourdomain.com',
    ],

    'allowed_origins_patterns' => [
        '#http://.*localhost.*#',
        '#http://.*127\.0\.0\.1.*#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Authorization'],

    'max_age' => 0,

    'supports_credentials' => true,

    // If using cookies or Sanctum SPA, set to true; Bearer tokens work either way
    'supports_credentials' => true,

];
