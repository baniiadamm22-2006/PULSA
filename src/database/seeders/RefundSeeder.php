<?php

namespace Database\Seeders;

use App\Models\Refund;
use Illuminate\Database\Seeder;

class RefundSeeder extends Seeder
{
    public function run(): void
    {
        // Tidak membuat data finansial/transaksi palsu.
        // Data ini dibuat melalui business service saat proses yang sah terjadi.
    }
}
