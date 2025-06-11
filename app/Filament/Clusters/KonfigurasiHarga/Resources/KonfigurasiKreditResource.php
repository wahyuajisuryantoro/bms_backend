<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\KonfigurasiKredit;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Clusters\KonfigurasiHarga;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource\Pages;
use App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource\RelationManagers;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class KonfigurasiKreditResource extends Resource
{
    protected static ?string $model = KonfigurasiKredit::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationLabel = 'Konfigurasi Kredit';
    protected static ?string $modelLabel = 'Konfigurasi Kredit';
    protected static ?string $pluralModelLabel = 'Konfigurasi Kredit';

    protected static ?string $cluster = KonfigurasiHarga::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Template')
                    ->description('Atur informasi dasar template kredit')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nama_template')
                                    ->label('Nama Template')
                                    ->placeholder('Contoh: Standard BCA, Promo Ramadan')
                                    ->required()
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true)
                                    ->helperText('Nama unik untuk template ini'),

                                Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->default(true)
                                    ->helperText('Template aktif bisa digunakan untuk mobil baru')
                            ]),


                    ]),

                Section::make('Pengaturan Tenor & Bunga')
                    ->description('Tambahkan tenor (lama cicilan) dan bunga per tahun yang tersedia')
                    ->schema([
                        Repeater::make('tenor_bunga_items')
                            ->label('')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('tenor')
                                            ->label('Tenor (Bulan)')
                                            ->placeholder('12')
                                            ->required()
                                            ->numeric()
                                            ->minValue(6)
                                            ->maxValue(72)
                                            ->suffix('bulan')
                                            ->helperText('6-72 bulan'),

                                        TextInput::make('bunga')
                                            ->label('Bunga per Tahun')
                                            ->placeholder('8.5')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(50)
                                            ->step(0.1)
                                            ->suffix('% /tahun')
                                            ->helperText('0-50%'),


                                    ])
                            ])
                            ->defaultItems(2)
                            ->addActionLabel('+ Tambah Tenor')
                            ->minItems(1)
                            ->maxItems(10)
                            ->deleteAction(
                                fn(Action $action) => $action->label('Hapus')
                            )
                            ->live()

                            ->columnSpanFull()
                    ]),

                Section::make('Pratinjau Template')
                    ->description('Ringkasan template yang akan dibuat')
                    ->schema([
                        Forms\Components\Placeholder::make('preview_info')
                            ->label('')
                            ->content(function ($get) {
                                $items = $get('tenor_bunga_items') ?? [];
                                if (empty($items)) {
                                    return 'Belum ada tenor yang ditambahkan';
                                }

                                $preview = [];
                                foreach ($items as $item) {
                                    if (isset($item['tenor']) && isset($item['bunga'])) {
                                        $preview[] = $item['tenor'] . ' bulan (' . $item['bunga'] . '% /tahun)';
                                    }
                                }

                                return 'Tenor tersedia: ' . implode(', ', $preview);
                            })
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_template')
                    ->label('Template')
                    ->searchable()
                    ->sortable(),



                // FIX: Perbaiki tenor tersedia
                TextColumn::make('tenor_display')
                    ->label('Tenor Tersedia')
                    ->getStateUsing(function ($record) {
                        // Debug data
                        if (empty($record->tenor_bunga_config)) {
                            return 'Belum ada tenor';
                        }

                        // Pastikan data adalah array
                        $config = $record->tenor_bunga_config;
                        if (is_string($config)) {
                            $config = json_decode($config, true);
                        }

                        if (!is_array($config) || empty($config)) {
                            return 'Data tidak valid';
                        }

                        $tenors = array_keys($config);
                        $formatted = array_map(function ($tenor) {
                            return $tenor . ' bln';
                        }, $tenors);

                        return implode(', ', $formatted);
                    })
                    ->badge()
                    ->color('info'),

                // FIX: Perbaiki range bunga
                TextColumn::make('bunga_display')
                    ->label('Range Bunga')
                    ->getStateUsing(function ($record) {
                        if (empty($record->tenor_bunga_config)) {
                            return '-';
                        }

                        $config = $record->tenor_bunga_config;
                        if (is_string($config)) {
                            $config = json_decode($config, true);
                        }

                        if (!is_array($config) || empty($config)) {
                            return 'Data tidak valid';
                        }

                        $rates = array_values($config);
                        $min = min($rates);
                        $max = max($rates);

                        return $min === $max ? $min . '%' : $min . '% - ' . $max . '%';
                    })
                    ->badge()
                    ->color('success'),

                // FIX: Perbaiki count usage
                TextColumn::make('usage_count')
                    ->label('Digunakan')
                    ->getStateUsing(function ($record) {
                        try {
                            return $record->opsiPembayaran()->count();
                        } catch (\Exception $e) {
                            return 0;
                        }
                    })
                    ->suffix(' mobil')
                    ->badge()
                    ->color(fn($state) => $state > 0 ? 'warning' : 'gray'),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => $state ? 'Aktif' : 'Non-aktif')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'danger'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('Semua')
                    ->trueLabel('Aktif')
                    ->falseLabel('Non-aktif')
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit'),
                Tables\Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->before(function ($record) {
                        if ($record->opsiPembayaran()->count() > 0) {
                            Notification::make()
                                ->title('Tidak dapat menghapus')
                                ->body('Template ini sedang digunakan oleh ' . $record->opsiPembayaran()->count() . ' mobil')
                                ->danger()
                                ->send();

                            return false;
                        }
                    })
            ])
            ->bulkActions([
                BulkAction::make('activate')
                    ->label('Aktifkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $records->each->update(['is_active' => true]);

                        Notification::make()
                            ->title('Berhasil diaktifkan')
                            ->body($records->count() . ' template telah diaktifkan')
                            ->success()
                            ->send();
                    }),

                BulkAction::make('deactivate')
                    ->label('Non-aktifkan')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (Collection $records) {
                        $records->each->update(['is_active' => false]);

                        Notification::make()
                            ->title('Berhasil dinonaktifkan')
                            ->body($records->count() . ' template telah dinonaktifkan')
                            ->warning()
                            ->send();
                    }),

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (Collection $records) {
                            $used = $records->filter(function ($record) {
                                return $record->opsiPembayaran()->count() > 0;
                            });

                            if ($used->count() > 0) {
                                Notification::make()
                                    ->title('Tidak dapat menghapus')
                                    ->body($used->count() . ' template sedang digunakan oleh mobil')
                                    ->danger()
                                    ->send();

                                return false;
                            }
                        })
                ])
            ])
            ->defaultSort('created_at', 'desc');
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKonfigurasiKredits::route('/'),
            'create' => Pages\CreateKonfigurasiKredit::route('/create'),
            'edit' => Pages\EditKonfigurasiKredit::route('/{record}/edit'),
        ];
    }

    public static function calculatePreviewAngsuran(int $tenor, float $bunga): string
    {
        $harga = 200000000; // 200 juta
        $dpPercentage = 20; // 20%

        $dpAmount = ($dpPercentage / 100) * $harga;
        $pokokKredit = $harga - $dpAmount;
        $bungaBulanan = $bunga / 12 / 100;

        if ($bungaBulanan <= 0 || $tenor <= 0) {
            return 'Rp 0';
        }

        $angsuranBulanan = $pokokKredit * $bungaBulanan *
            pow((1 + $bungaBulanan), $tenor) /
            (pow((1 + $bungaBulanan), $tenor) - 1);

        return 'Rp ' . number_format($angsuranBulanan, 0, ',', '.');
    }
}
