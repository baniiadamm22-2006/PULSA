<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
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

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationGroup = 'Katalog';
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
                Select::make('category_id')->relationship('category', 'name')->searchable()->preload()->required(),
                Select::make('provider_id')->relationship('provider', 'name')->searchable()->preload()->required(),
                TextInput::make('code')->required()->maxLength(100)->unique(ignoreRecord: true),
                TextInput::make('name')->required()->maxLength(200),
                Textarea::make('description')->columnSpanFull(),
                TextInput::make('cost_price')->label('Harga Modal')->required()->numeric()->minValue(0),
                TextInput::make('selling_price')->label('Harga Jual')->required()->numeric()->minValue(0),
                TextInput::make('quota')->maxLength(100),
                TextInput::make('validity_days')->label('Masa berlaku (hari)')->numeric()->minValue(1),
                TagsInput::make('countries')->label('Negara roaming'),
                Textarea::make('terms')->label('Ketentuan')->columnSpanFull(),
                KeyValue::make('attributes')->label('Atribut lainnya')->columnSpanFull(),
                Toggle::make('is_active')->default(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->limit(45)->sortable(),
                Tables\Columns\TextColumn::make('code')->limit(45)->sortable(),
                Tables\Columns\TextColumn::make('category.name')->limit(45)->sortable()->label('Kategori'),
                Tables\Columns\TextColumn::make('provider.name')->limit(45)->sortable()->label('Provider'),
                Tables\Columns\TextColumn::make('selling_price')->money('IDR')->sortable(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['category', 'provider']);
    }
}
