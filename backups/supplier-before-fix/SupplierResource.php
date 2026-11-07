<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupplierResource\Pages;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
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
                    Forms\Components\TextInput::make('name')->label('Nama Supplier')
                        ->required()->maxLength(160),
                    Forms\Components\TextInput::make('code')->label('Kode Supplier')
                        ->required()->maxLength(64)->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('configuration_reference')
                        ->label('Referensi Konfigurasi')->maxLength(255)
                        ->helperText('Nama referensi konfigurasi, bukan API key atau password.')
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')->label('Aktif')->default(false),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Supplier')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code')->label('Kode')->searchable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
                \Filament\Tables\Actions\DeleteAction::make(),
            ]);
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
