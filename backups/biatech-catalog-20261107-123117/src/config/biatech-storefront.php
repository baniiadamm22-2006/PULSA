<?php

return [
    // 'demo' never reads or writes product/order/payment tables.
    // Change to 'database' after Category/Product models and migrations are ready.
    'catalog_source' => env('BIATECH_CATALOG_SOURCE', 'demo'),
    'brand_name' => env('BIATECH_BRAND_NAME', 'BIATECH'),
    'support_email' => env('BIATECH_SUPPORT_EMAIL'),
    'support_whatsapp' => env('BIATECH_SUPPORT_WHATSAPP'),
];
