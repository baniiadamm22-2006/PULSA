<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::query()->firstOrCreate(
            ['code' => 'DIGIFLAZZ'],
            [
                'name' => 'Digiflazz',
                'configuration_reference' => 'DIGIFLAZZ',
                'is_active' => false,
            ],
        );
    }
}
