<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ['name' => 'Telkomsel', 'code' => 'TELKOMSEL'],
            ['name' => 'Indosat', 'code' => 'INDOSAT'],
            ['name' => 'XL', 'code' => 'XL'],
            ['name' => 'AXIS', 'code' => 'AXIS'],
            ['name' => 'Tri', 'code' => 'TRI'],
            ['name' => 'Smartfren', 'code' => 'SMARTFREN'],
            ['name' => 'PLN', 'code' => 'PLN'],
            ['name' => 'Roaming Internasional', 'code' => 'ROAMING_GLOBAL'],
        ];

        foreach ($providers as $provider) {
            Provider::query()->firstOrCreate(
                ['code' => $provider['code']],
                ['name' => $provider['name'], 'is_active' => true],
            );
        }
    }
}
