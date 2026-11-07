<?php

namespace App\Filament\Admin\Resources\PaymentWebhookResource\Pages;

use App\Filament\Admin\Resources\PaymentWebhookResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentWebhook extends CreateRecord
{
    protected static string $resource = PaymentWebhookResource::class;
}
