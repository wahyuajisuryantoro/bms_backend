<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OpsiPembayaran;
use Filament\Resources\Resource;
use App\Models\KonfigurasiKredit;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Clusters\KonfigurasiHarga;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource\Pages;
use App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource\RelationManagers;

class OpsiPembayaranResource extends Resource
{
    protected static ?string $model = OpsiPembayaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationLabel = 'Opsi Pembayaran';
    protected static ?string $cluster = KonfigurasiHarga::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Dasar')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('mobil_id')
                                    ->label('Mobil')
                                    ->relationship('mobil', 'nama_mobil')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->columnSpan(1),
                                    
                                TextInput::make('harga')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->step(0.01)
                                    ->required()
                                    ->columnSpan(1),
                            ]),
                            
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true)
                                    ->columnSpan(1),
                                    
                                Toggle::make('is_kredit')
                                    ->label('Tersedia Kredit')
                                    ->default(false)
                                    ->live()
                                    ->columnSpan(1),
                            ]),
                    ]),
                    
                Section::make('Konfigurasi Kredit')
                    ->schema([
                        Select::make('konfigurasi_kredit_id')
                            ->label('Konfigurasi Kredit')
                            ->relationship('konfigurasiKredit', 'nama_template')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->visible(fn (Get $get): bool => $get('is_kredit'))
                            ->required(fn (Get $get): bool => $get('is_kredit'))
                            ->helperText('Pilih konfigurasi kredit yang akan digunakan untuk mobil ini'),
                    ])
                    ->visible(fn (Get $get): bool => $get('is_kredit')),
            ]);
    }

   public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mobil.nama_mobil')
                    ->label('Mobil')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),
                    
                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                    
                IconColumn::make('is_kredit')
                    ->label('Kredit')
                    ->boolean(),
                    
                TextColumn::make('konfigurasiKredit.nama_template')
                    ->label('Konfigurasi Kredit')
                    ->placeholder('Tidak ada')
                    ->searchable(),
                    
                TextColumn::make('available_methods')
                    ->label('Metode Tersedia')
                    ->badge()
                    ->separator(',')
                    ->color(fn (string $state): string => match ($state) {
                        'Cash' => 'success',
                        'Kredit' => 'warning',
                        default => 'gray',
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Aktif'),
                    
                Tables\Filters\TernaryFilter::make('is_kredit')
                    ->label('Tersedia Kredit'),
                    
                Tables\Filters\SelectFilter::make('mobil_id')
                    ->label('Mobil')
                    ->relationship('mobil', 'nama_mobil')
                    ->searchable()
                    ->preload(),
                    
                Tables\Filters\SelectFilter::make('konfigurasi_kredit_id')
                    ->label('Konfigurasi Kredit')
                    ->relationship('konfigurasiKredit', 'nama_template')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_active' => true]);
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                        
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_active' => false]);
                            });
                        })
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOpsiPembayarans::route('/'),
            'create' => Pages\CreateOpsiPembayaran::route('/create'),
            'edit' => Pages\EditOpsiPembayaran::route('/{record}/edit'),
        ];
    }
}
