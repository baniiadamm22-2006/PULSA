<?php

declare(strict_types=1);

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BiatechIntegrationStatus extends StatsOverviewWidget
{
    protected static ?int $sort = 2;

    protected static ?string $pollingInterval = null;

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        return [
            Stat::make('API Reseller Teman', 'Belum terverifikasi')
                ->description('Menunggu kontrak API katalog, saldo dan transaksi')
                ->descriptionIcon('heroicon-m-server')
                ->color('warning'),

            Stat::make('Midtrans', 'Belum terverifikasi')
                ->description('Webhook dan pembayaran asli belum diuji end-to-end')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('warning'),

            Stat::make('Telegram / Fulfillment', 'Belum terverifikasi')
                ->description('Menunggu integrasi status dari reseller teman')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('warning'),
        ];
    }
}
