<?php

namespace App\Filament\Admin\Resources\PaymentWebhookResource\Pages;

use App\Filament\Admin\Resources\PaymentWebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPaymentWebhooks extends ListRecords
{
    protected static string $resource = PaymentWebhookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
