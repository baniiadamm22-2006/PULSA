<?php

declare(strict_types=1);

return [
    // The storefront reads categories and products from MariaDB by default.
    // Set BIATECH_CATALOG_SOURCE=demo to preview only without database records.
    'catalog_source' => env('BIATECH_CATALOG_SOURCE', 'database'),

    'brand_name' => env('BIATECH_BRAND_NAME', 'BIATECH'),
    'support_email' => env('BIATECH_SUPPORT_EMAIL'),
    'support_whatsapp' => env('BIATECH_SUPPORT_WHATSAPP'),
];
