<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Livewire Routes — Original Boilerplate
|--------------------------------------------------------------------------
|
| Keep the original boilerplate configuration.
| Required for Filament v3 authentication and Livewire requests.
|
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(
        config('app.asset_prefix') . '/livewire/update',
        $handle
    );
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(
        config('app.asset_prefix') . '/livewire/livewire.js',
        $handle
    );
});

/*
|--------------------------------------------------------------------------
| BIATECH Storefront Routes
|--------------------------------------------------------------------------
|
| Register customer-facing routes without changing the
| original Filament admin panel.
|
*/

require_once __DIR__ . '/biatech-storefront.php';
