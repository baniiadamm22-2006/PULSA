<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierProductResource\Pages;
use App\Models\SupplierProduct;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\KeyValue;

class SupplierProductResource extends Resource
{
    protected static ?string $model = SupplierProduct::class;
    protected static ?string $navigationGroup = 'Supplier';
    protected static ?string $navigationIcon = 'heroicon-o-link';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
                Select::make('supplier_id')->relationship('supplier', 'name')->searchable()->preload()->required(),
                Select::make('product_id')->relationship('product', 'name')->searchable()->preload()->required(),
                TextInput::make('supplier_product_code')->required()->maxLength(120),
                TextInput::make('supplier_price')->numeric()->minValue(0),
                Toggle::make('is_active')->default(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')->limit(45)->sortable()->label('Supplier'),
                Tables\Columns\TextColumn::make('product.name')->limit(45)->sortable()->label('Produk'),
                Tables\Columns\TextColumn::make('supplier_product_code')->limit(45)->sortable(),
                Tables\Columns\TextColumn::make('supplier_price')->money('IDR')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierProducts::route('/'),
            'create' => Pages\CreateSupplierProduct::route('/create'),
            'edit' => Pages\EditSupplierProduct::route('/{record}/edit'),
        ];
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
