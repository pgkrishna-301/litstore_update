<?php

return [
    'paths' => ['api/*','banner-image/*', 'add-image/*', 'color-image/*', 'storage/*',  'uploads/*', 'profile-image/*'],  // Allow access to profile images and api routes
    'allowed_methods' => ['*'],  // Allow all methods (GET, POST, etc.)
    'allowed_origins' => ['*'],  // Allow all origins, or specify your frontend URL (e.g., 'http://localhost:3000' for development)
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
