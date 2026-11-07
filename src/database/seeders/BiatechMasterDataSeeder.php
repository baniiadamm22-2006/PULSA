<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BiatechMasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            ProviderSeeder::class,
            SupplierSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
