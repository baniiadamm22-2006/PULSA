<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentWebhookResource\Pages;
use App\Models\PaymentWebhook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentWebhookResource extends Resource
{
    protected static ?string $model = PaymentWebhook::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Record transaksi dibuat oleh proses checkout, gateway dan supplier, bukan input admin.
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Placeholder::make('informasi')
                ->label('Data transaksi')
                ->content('Data ini dicatat otomatis dan tidak dapat dibuat atau diedit manual.'),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Detail Notifikasi Pembayaran')
                ->schema([
                Infolists\Components\TextEntry::make('gateway')->label('Gateway'),
                Infolists\Components\TextEntry::make('signature_valid')->label('Tanda tangan valid'),
                Infolists\Components\TextEntry::make('processed_at')->label('Diproses'),
                Infolists\Components\TextEntry::make('created_at')->label('Diterima'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gateway')->label('Gateway')->badge(),
                Tables\Columns\IconColumn::make('signature_valid')->label('Signature valid')->boolean(),
                Tables\Columns\TextColumn::make('processed_at')->label('Diproses')->dateTime(),
                Tables\Columns\TextColumn::make('created_at')->label('Diterima')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentWebhooks::route('/'),
        ];
    }
}
