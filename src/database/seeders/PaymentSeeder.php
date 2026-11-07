<?php

namespace Database\Seeders;

use App\Models\Payment;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Tidak membuat data finansial/transaksi palsu.
        // Data ini dibuat melalui business service saat proses yang sah terjadi.
    }
}
