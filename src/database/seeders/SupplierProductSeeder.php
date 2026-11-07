<?php

namespace Database\Seeders;

use App\Models\SupplierProduct;
use Illuminate\Database\Seeder;

class SupplierProductSeeder extends Seeder
{
    public function run(): void
    {
        // Tidak membuat data finansial/transaksi palsu.
        // Data ini dibuat melalui business service saat proses yang sah terjadi.
    }
}
