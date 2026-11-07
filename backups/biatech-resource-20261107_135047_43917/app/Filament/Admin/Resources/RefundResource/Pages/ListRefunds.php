<?php

namespace App\Filament\Admin\Resources\RefundResource\Pages;

use App\Filament\Admin\Resources\RefundResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRefunds extends ListRecords
{
    protected static string $resource = RefundResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
