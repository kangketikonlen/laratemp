<?php

use Illuminate\Support\Env;

return [
    'enabled' => filter_var(
        Env::get('BOOTSTRAP_ADMIN_ENABLED', Env::get('APP_ENV', 'production') !== 'production'),
        FILTER_VALIDATE_BOOL
    ),
    'name' => Env::get('BOOTSTRAP_ADMIN_NAME', 'Administrator'),
    'email' => Env::get('BOOTSTRAP_ADMIN_EMAIL', 'admin@example.com'),
    'username' => Env::get('BOOTSTRAP_ADMIN_USERNAME', 'support'),
    'password' => Env::get('BOOTSTRAP_ADMIN_PASSWORD'),
];
