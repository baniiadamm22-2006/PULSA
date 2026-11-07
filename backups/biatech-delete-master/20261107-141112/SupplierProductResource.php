<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SupplierProductResource\Pages;
use App\Models\SupplierProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupplierProductResource extends Resource
{
    protected static ?string $model = SupplierProduct::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\Section::make('Pemetaan Produk Supplier')
                ->schema([
                    Forms\Components\Select::make('supplier_id')->label('Supplier')
                        ->relationship('supplier', 'name')->searchable()->preload()->required(),
                    Forms\Components\Select::make('product_id')->label('Produk BIATECH')
                        ->relationship('product', 'name')->searchable()->preload()->required()
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule, Forms\Get $get): \Illuminate\Validation\Rules\Unique =>
                                $rule->where('supplier_id', $get('supplier_id')),
                        ),
                    Forms\Components\TextInput::make('supplier_product_code')
                        ->label('Kode Produk di Supplier')->required()->maxLength(120)
                        ->unique(
                            ignoreRecord: true,
                            modifyRuleUsing: fn (\Illuminate\Validation\Rules\Unique $rule, Forms\Get $get): \Illuminate\Validation\Rules\Unique =>
                                $rule->where('supplier_id', $get('supplier_id')),
                        ),
                    Forms\Components\TextInput::make('supplier_price')
                        ->label('Harga Supplier (Rp)')->numeric()->minValue(0),
                    Forms\Components\Toggle::make('is_active')->label('Aktif')
                        ->default(false)
                        ->helperText('Aktifkan hanya setelah kode produk dan koneksi supplier terverifikasi.'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')->label('Supplier')->searchable(),
                Tables\Columns\TextColumn::make('product.name')->label('Produk')->searchable(),
                Tables\Columns\TextColumn::make('supplier_product_code')->label('SKU Supplier')->searchable(),
                Tables\Columns\TextColumn::make('supplier_price')->label('Harga Modal')->money('IDR')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierProducts::route('/'),
            'create' => Pages\CreateSupplierProduct::route('/create'),
            'edit' => Pages\EditSupplierProduct::route('/{record}/edit'),
        ];
    }
}
