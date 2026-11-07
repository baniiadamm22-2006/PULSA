<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminActivityLogResource\Pages;
use App\Models\AdminActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AdminActivityLogResource extends Resource
{
    protected static ?string $model = AdminActivityLog::class;

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
            Infolists\Components\Section::make('Detail Aktivitas Admin')
                ->schema([
                Infolists\Components\TextEntry::make('user.name')->label('Admin'),
                Infolists\Components\TextEntry::make('action')->label('Aksi'),
                Infolists\Components\TextEntry::make('subject_type')->label('Jenis objek'),
                Infolists\Components\TextEntry::make('subject_id')->label('ID objek'),
                Infolists\Components\TextEntry::make('reason')->label('Alasan'),
                Infolists\Components\TextEntry::make('created_at')->label('Waktu'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Admin')->searchable(),
                Tables\Columns\TextColumn::make('action')->label('Aksi')->searchable(),
                Tables\Columns\TextColumn::make('subject_type')->label('Objek')->limit(35),
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
            'index' => Pages\ListAdminActivityLogs::route('/'),
        ];
    }
}
