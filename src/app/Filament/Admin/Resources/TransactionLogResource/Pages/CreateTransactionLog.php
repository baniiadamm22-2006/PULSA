<?php

namespace App\Filament\Admin\Resources\TransactionLogResource\Pages;

use App\Filament\Admin\Resources\TransactionLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransactionLog extends CreateRecord
{
    protected static string $resource = TransactionLogResource::class;
}
