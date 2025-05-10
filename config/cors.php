<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'uploads/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'], // or specify: ['http://localhost:3000']

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
