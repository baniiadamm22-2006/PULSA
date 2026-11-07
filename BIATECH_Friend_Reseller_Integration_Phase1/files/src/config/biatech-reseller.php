<?php

declare(strict_types=1);

return [
    'enabled' => filter_var(env('FRIEND_RESELLER_ENABLED', false), FILTER_VALIDATE_BOOLEAN),

    'base_url' => env('FRIEND_RESELLER_BASE_URL', ''),
    'token' => env('FRIEND_RESELLER_TOKEN', ''),

    // 'bearer' => Authorization: Bearer <token>
    // 'header' => <auth_header>: <token>
    'auth_mode' => env('FRIEND_RESELLER_AUTH_MODE', 'bearer'),
    'auth_header' => env('FRIEND_RESELLER_AUTH_HEADER', 'X-API-Key'),

    // Replace these example paths with paths from your friend's API documentation.
    'catalog_path' => env('FRIEND_RESELLER_CATALOG_PATH', ''),
    'balance_path' => env('FRIEND_RESELLER_BALANCE_PATH', ''),

    // Only used for reading the response, never for modifying products.
    'catalog_items_field' => env('FRIEND_RESELLER_CATALOG_ITEMS_FIELD', 'data'),
    'balance_amount_field' => env('FRIEND_RESELLER_BALANCE_AMOUNT_FIELD', 'data.balance'),

    'connect_timeout' => (int) env('FRIEND_RESELLER_CONNECT_TIMEOUT', 5),
    'timeout' => (int) env('FRIEND_RESELLER_TIMEOUT', 15),
];
