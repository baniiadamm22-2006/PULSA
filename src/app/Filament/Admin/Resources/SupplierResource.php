<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Supplier')
                ->description('Supplier baru tetap nonaktif sampai integrasi API siap.')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Supplier')
                        ->required()
                        ->maxLength(160),
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Supplier')
                        ->required()
                        ->maxLength(64)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('configuration_reference')
                        ->label('Referensi Konfigurasi')
                        ->maxLength(255)
                        ->helperText('Nama referensi konfigurasi, bukan API key atau password.')
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(false),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Supplier')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supplier_products_count')
                    ->label('SKU Terhubung')
                    ->counts('supplierProducts'),
                Tables\Columns\TextColumn::make('transactions_count')
                    ->label('Transaksi')
                    ->counts('transactions'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->before(function (Tables\Actions\DeleteAction $action, Supplier $record): void {
                        if (! static::hasReferences($record)) {
                            return;
                        }

                        static::notifyDeletionBlocked($record);
                        $action->cancel();
                    }),
            ])
            // Bulk Delete sengaja tidak disediakan agar tidak melewati pemeriksaan relasi.
            ->bulkActions([]);
    }

    public static function hasReferences(Supplier $supplier): bool
    {
        return $supplier->supplierProducts()->exists()
            || $supplier->transactions()->exists();
    }

    public static function notifyDeletionBlocked(Supplier $supplier): void
    {
        $skuCount = $supplier->supplierProducts()->count();
        $transactionCount = $supplier->transactions()->count();

        Notification::make()
            ->title('Supplier tidak dapat dihapus')
            ->body(
                "Supplier ini masih terhubung ke {$skuCount} SKU dan {$transactionCount} transaksi. " .
                'Nonaktifkan supplier jika tidak digunakan, atau pindahkan pemetaan SKU secara aman.'
            )
            ->warning()
            ->persistent()
            ->send();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
