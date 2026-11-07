<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // Tidak membuat data finansial/transaksi palsu.
        // Data ini dibuat melalui business service saat proses yang sah terjadi.
    }
}
