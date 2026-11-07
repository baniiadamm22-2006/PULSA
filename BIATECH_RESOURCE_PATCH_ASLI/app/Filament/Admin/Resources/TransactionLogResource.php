<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TransactionLogResource\Pages;
use App\Models\TransactionLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TransactionLogResource extends Resource
{
    protected static ?string $model = TransactionLog::class;

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
            Infolists\Components\Section::make('Detail Log Transaksi')
                ->schema([
                Infolists\Components\TextEntry::make('transaction.local_reference')->label('Referensi transaksi'),
                Infolists\Components\TextEntry::make('event')->label('Event'),
                Infolists\Components\TextEntry::make('status')->label('Status'),
                Infolists\Components\TextEntry::make('message')->label('Pesan'),
                Infolists\Components\TextEntry::make('created_at')->label('Waktu'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transaction.local_reference')->label('Referensi')->searchable(),
                Tables\Columns\TextColumn::make('event')->label('Event')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->label('Detail'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactionLogs::route('/'),
        ];
    }
}
