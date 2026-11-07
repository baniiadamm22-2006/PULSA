<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Reseller\FriendResellerClient;
use Illuminate\Console\Command;
use Throwable;

final class CheckFriendReseller extends Command
{
    protected $signature = 'biatech:reseller-check
        {--catalog : Periksa endpoint katalog dan struktur JSON}
        {--balance : Periksa endpoint saldo dan struktur JSON}';

    protected $description = 'Periksa koneksi API reseller tanpa mengubah database atau saldo.';

    public function handle(FriendResellerClient $client): int
    {
        $checkCatalog = (bool) $this->option('catalog');
        $checkBalance = (bool) $this->option('balance');

        if (! $checkCatalog && ! $checkBalance) {
            $checkCatalog = true;
            $checkBalance = true;
        }

        if (! config('biatech-reseller.enabled')) {
            $this->error('Koneksi reseller OFF. Atur FRIEND_RESELLER_ENABLED=true setelah API siap.');

            return self::FAILURE;
        }

        $failed = false;

        if ($checkCatalog) {
            try {
                $payload = $client->catalog();
                $field = (string) config('biatech-reseller.catalog_items_field');
                $items = data_get($payload, $field);

                if (! is_array($items) || ! array_is_list($items)) {
                    $this->warn('Katalog terhubung, tetapi path daftar produk belum cocok.');
                    $this->line('Kunci JSON tingkat atas: ' . implode(', ', array_map('strval', array_keys($payload))));
                    $this->line('Periksa FRIEND_RESELLER_CATALOG_ITEMS_FIELD.');
                    $failed = true;
                } else {
                    $this->info('Katalog API terhubung: ' . count($items) . ' item pada respons ini.');
                    $this->warn('Belum ada data yang ditulis ke MariaDB.');
                }
            } catch (Throwable $exception) {
                $this->error('Katalog API gagal: ' . $this->safeMessage($exception));
                $failed = true;
            }
        }

        if ($checkBalance) {
            try {
                $payload = $client->balance();
                $field = (string) config('biatech-reseller.balance_amount_field');
                $amount = data_get($payload, $field);

                if (! is_numeric($amount) || (float) $amount < 0) {
                    $this->warn('Endpoint saldo terhubung, tetapi path nominal belum cocok.');
                    $this->line('Kunci JSON tingkat atas: ' . implode(', ', array_map('strval', array_keys($payload))));
                    $this->line('Periksa FRIEND_RESELLER_BALANCE_AMOUNT_FIELD.');
                    $failed = true;
                } else {
                    $this->info('Saldo deposit reseller (sesuai respons API): Rp ' . number_format((float) $amount, 0, ',', '.'));
                    $this->warn('Ini BUKAN saldo Midtrans atau dana kas BIATECH.');
                }
            } catch (Throwable $exception) {
                $this->error('Saldo API gagal: ' . $this->safeMessage($exception));
                $failed = true;
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }

    private function safeMessage(Throwable $exception): string
    {
        // Do not print HTTP exception details that may include private URLs or tokens.
        return $exception instanceof \InvalidArgumentException || $exception instanceof \RuntimeException
            ? $exception->getMessage()
            : 'Koneksi gagal. Periksa DNS, TLS, firewall, atau timeout di log internal.';
    }
}
