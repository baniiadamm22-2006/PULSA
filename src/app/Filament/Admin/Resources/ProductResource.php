<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Informasi Produk')
                ->schema([
                    Select::make('category_id')
                        ->label('Kategori')
                        ->relationship('category', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('provider_id')
                        ->label('Provider')
                        ->relationship('provider', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('code')
                        ->label('Kode Produk')
                        ->placeholder('TSEL25')
                        ->required()
                        ->maxLength(100)
                        ->unique(ignoreRecord: true),
                    TextInput::make('name')
                        ->label('Nama Produk')
                        ->placeholder('Pulsa Telkomsel 25.000')
                        ->required()
                        ->maxLength(200),
                    Textarea::make('description')
                        ->label('Deskripsi Produk')
                        ->rows(3)
                        ->columnSpanFull(),
                ])
                ->columns(2),
            Section::make('Harga dan Detail')
                ->schema([
                    TextInput::make('cost_price')
                        ->label('Harga Modal')
                        ->prefix('Rp')
                        ->numeric()
                        ->minValue(0)
                        ->default(0)
                        ->required(),
                    TextInput::make('selling_price')
                        ->label('Harga Jual')
                        ->prefix('Rp')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                    TextInput::make('quota')
                        ->label('Kuota / Nominal')
                        ->placeholder('25.000 / 10 GB')
                        ->maxLength(100),
                    TextInput::make('validity_days')
                        ->label('Masa Berlaku (hari)')
                        ->integer()
                        ->minValue(1),
                    TagsInput::make('countries')
                        ->label('Negara Roaming')
                        ->helperText('Isi hanya untuk produk roaming.')
                        ->columnSpanFull(),
                    Textarea::make('terms')
                        ->label('Syarat dan Ketentuan')
                        ->rows(3)
                        ->columnSpanFull(),
                    KeyValue::make('attributes')
                        ->label('Atribut Tambahan')
                        ->columnSpanFull(),
                    Toggle::make('is_active')
                        ->label('Tampilkan di Landing Page')
                        ->helperText('Produk hanya muncul jika produk dan kategorinya aktif.')
                        ->default(true)
                        ->inline(false),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('provider.name')
                    ->label('Provider')
                    ->sortable(),
                Tables\Columns\TextColumn::make('selling_price')
                    ->label('Harga Jual')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['category', 'provider']);
    }
}
