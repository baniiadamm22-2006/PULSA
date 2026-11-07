<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentWebhookResource\Pages;
use App\Models\PaymentWebhook;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;

class PaymentWebhookResource extends Resource
{
    protected static ?string $model = PaymentWebhook::class;
    protected static ?string $navigationGroup = 'Keuangan';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?int $navigationSort = 9;

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
                TextEntry::make('gateway')->label('Gateway'),
                TextEntry::make('event_fingerprint')->label('Event Fingerprint'),
                TextEntry::make('processed_at')->label('Processed At'),
                TextEntry::make('created_at')->label('Created At'),
                IconEntry::make('signature_valid')->boolean(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gateway')->limit(45)->sortable(),
                Tables\Columns\TextColumn::make('event_fingerprint')->limit(45),
                Tables\Columns\IconColumn::make('signature_valid')->boolean(),
                Tables\Columns\TextColumn::make('processed_at')->dateTime('d M Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPaymentWebhooks::route('/'),
            'view' => Pages\ViewPaymentWebhook::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }
}
