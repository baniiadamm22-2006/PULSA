<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BiatechOperationalStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '60s';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        $orderCount = $this->orderCount();
        $paidAmount = $this->paidAmount();
        $grossMargin = $this->grossMargin();

        return [
            Stat::make('Saldo Deposit Reseller', '—')
                ->description('Menunggu endpoint saldo dari API teman')
                ->descriptionIcon('heroicon-m-wallet')
                ->color('warning'),

            Stat::make(
                'Pembayaran Lunas Tercatat',
                $paidAmount === null ? 'Belum tersedia' : $this->rupiah($paidAmount),
            )
                ->description('Berdasarkan status payment; bukan saldo Midtrans')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make(
                'Total Pesanan',
                $orderCount === null ? 'Belum tersedia' : number_format($orderCount, 0, ',', '.'),
            )
                ->description('Jumlah order di database BIATECH')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('primary'),

            Stat::make(
                'Estimasi Margin Produk',
                $grossMargin === null ? 'Belum tersedia' : $this->rupiah($grossMargin),
            )
                ->description('Transaksi sukses; belum dikurangi biaya gateway/refund')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),
        ];
    }

    private function orderCount(): ?int
    {
        if (! Schema::hasTable('orders')) {
            return null;
        }

        return DB::table('orders')->count();
    }

    private function paidAmount(): ?float
    {
        if (! Schema::hasTable('payments') ||
            ! Schema::hasColumns('payments', ['status', 'amount'])) {
            return null;
        }

        $query = DB::table('payments')
            ->whereIn('status', ['paid', 'settlement']);

        if (Schema::hasColumn('payments', 'gateway')) {
            $query->where('gateway', 'midtrans');
        }

        return (float) $query->sum('amount');
    }

    private function grossMargin(): ?float
    {
        if (! Schema::hasTable('order_items') ||
            ! Schema::hasTable('transactions') ||
            ! Schema::hasColumns('order_items', [
                'id',
                'quantity',
                'selling_price_snapshot',
                'cost_price_snapshot',
            ]) ||
            ! Schema::hasColumns('transactions', ['order_item_id', 'status'])) {
            return null;
        }

        // EXISTS mencegah margin terhitung dua kali jika ada retry transaksi.
        // Jangan menganggap margin ini sebagai laba bersih atau saldo kas.
        $value = DB::table('order_items as item')
            ->whereExists(function (Builder $query): void {
                $query->selectRaw('1')
                    ->from('transactions as transaction')
                    ->whereColumn('transaction.order_item_id', 'item.id')
                    ->where('transaction.status', 'success');
            })
            ->selectRaw(
                'COALESCE(SUM((item.selling_price_snapshot - item.cost_price_snapshot) * item.quantity), 0) AS total_margin',
            )
            ->value('total_margin');

        return (float) $value;
    }

    private function rupiah(float $amount): string
    {
        return 'Rp' . number_format($amount, 0, ',', '.');
    }
}
