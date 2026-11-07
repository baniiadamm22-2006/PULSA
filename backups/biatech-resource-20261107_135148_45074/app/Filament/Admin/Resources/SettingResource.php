<?php

declare(strict_types=1);

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {

        return $form->schema([
            Forms\Components\Section::make('Pengaturan Sistem')
                ->schema([
                    Forms\Components\TextInput::make('key')->label('Kunci Pengaturan')
                        ->required()->maxLength(150)->unique(ignoreRecord: true)
                        ->helperText('Contoh: site_name; tidak boleh sama dengan pengaturan lain.'),
                    Forms\Components\Select::make('value_type')->label('Tipe Nilai')
                        ->options([
                            'string' => 'Teks',
                            'number' => 'Angka',
                            'boolean' => 'Boolean',
                            'json' => 'JSON',
                        ])->default('string')->required(),
                    Forms\Components\Textarea::make('value')->label('Nilai')
                        ->rows(5)->columnSpanFull()
                        ->helperText('Jangan simpan API key, secret pembayaran, atau password pada setting publik.'),
                    Forms\Components\Toggle::make('is_public')->label('Boleh ditampilkan publik')
                        ->default(false)
                        ->helperText('Matikan untuk konfigurasi internal.'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->label('Kunci')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value_type')->label('Tipe')->badge(),
                Tables\Columns\IconColumn::make('is_public')->label('Publik')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->label('Diperbarui')->dateTime()->sortable(),
            ])
            ->defaultSort('id', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
