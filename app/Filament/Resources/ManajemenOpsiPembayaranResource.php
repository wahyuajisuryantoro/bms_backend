<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\OpsiPembayaran;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ManajemenOpsiPembayaranResource\Pages;

class ManajemenOpsiPembayaranResource extends Resource
{
    protected static ?string $model = OpsiPembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Manajemen Pembayaran';
    protected static ?string $navigationLabel = 'Opsi Pembayaran';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $isCash = $record ? $record->metode === 'Cash' : false;
        $isKredit = $record ? $record->metode === 'Kredit' : false;

        // Schema untuk form
        $schema = [];

        // Selalu tampilkan mobil_id (tidak bisa diubah jika dalam mode edit)
        $schema[] = Section::make('Informasi Mobil')
            ->schema([
                Forms\Components\Select::make('mobil_id')
                    ->label('Pilih Mobil')
                    ->relationship('mobil', 'nama_mobil')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled($record ? true : false) // Disable jika dalam mode edit
                    ->dehydrated(true) // Tetap kirim datanya meskipun disabled
                    ->columnSpanFull(),
            ]);

        // Tampilkan harga cash jika record adalah cash atau jika dalam mode create
        if (!$record || $isCash) {
            $schema[] = Section::make('Harga Cash')
                ->schema([
                    Forms\Components\TextInput::make('harga_cash')
                        ->label('Harga Cash')
                        ->required(!$isKredit) // Required jika bukan edit kredit
                        ->numeric()
                        ->prefix('Rp')
                        ->dehydrated(true)
                        ->default($isCash ? $record->harga : null) // Set nilai default jika edit
                        ->live(true),
                ]);
        }

        // Tampilkan form kredit jika record adalah kredit atau jika dalam mode create
        if (!$record || $isKredit) {
            // Jika dalam mode edit untuk satu record kredit
            if ($isKredit) {
                $schema[] = Section::make('Detail Kredit')
                    ->schema([
                        Fieldset::make('Detail Tenor')
                            ->schema([
                                Forms\Components\TextInput::make('tenor')
                                    ->label('Tenor (bulan)')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(72)
                                    ->step(1)
                                    ->default($record->tenor),

                                Forms\Components\TextInput::make('bunga')
                                    ->label('Bunga (%)')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->suffix('%')
                                    ->default($record->bunga),
                            ])
                            ->columns(2),

                        Fieldset::make('Pembayaran')
                            ->schema([
                                Forms\Components\TextInput::make('dp_minimal')
                                    ->label('DP Minimal')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->default($record->dp_minimal)
                                    ->live(),

                                Forms\Components\TextInput::make('angsuran_per_bulan')
                                    ->label('Angsuran per Bulan')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->minValue(0)
                                    ->default($record->angsuran_per_bulan)
                                    ->live(),
                            ])
                            ->columns(2),

                        Fieldset::make('Biaya Tambahan')
                            ->schema([
                                Forms\Components\TextInput::make('biaya_admin')
                                    ->label('Biaya Admin')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default($record->biaya_admin)
                                    ->live(),

                                Forms\Components\TextInput::make('biaya_asuransi')
                                    ->label('Biaya Asuransi')
                                    ->required()
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default($record->biaya_asuransi)
                                    ->live(),
                            ])
                            ->columns(2),

                        // Kalkulasi
                        Forms\Components\View::make('filament.components.kalkulasi-kredit')
                            ->extraAttributes(function (callable $get) {
                                $tenor = $get('tenor');
                                $angsuranPerBulan = $get('angsuran_per_bulan');
                                $dpMinimal = $get('dp_minimal');
                                $biayaAdmin = $get('biaya_admin');
                                $biayaAsuransi = $get('biaya_asuransi');

                                if ($tenor && $angsuranPerBulan) {
                                    $totalAngsuran = $angsuranPerBulan * $tenor;
                                    $totalBiaya = $totalAngsuran + $dpMinimal + $biayaAdmin + $biayaAsuransi;

                                    return [
                                        'data-tenor' => $tenor,
                                        'data-angsuran-per-bulan' => $angsuranPerBulan,
                                        'data-dp-minimal' => $dpMinimal,
                                        'data-biaya-admin' => $biayaAdmin,
                                        'data-biaya-asuransi' => $biayaAsuransi,
                                        'data-total-angsuran' => $totalAngsuran,
                                        'data-total-biaya' => $totalBiaya,
                                    ];
                                }

                                return [];
                            })
                    ]);
            } else {
                // Mode create atau group edit
                $schema[] = Section::make('Opsi Kredit')
                    ->schema([
                        Repeater::make('opsi_kredit')
                            ->schema([
                                // Detail Tenor
                                Fieldset::make('Detail Tenor')
                                    ->schema([
                                        Forms\Components\TextInput::make('tenor')
                                            ->label('Tenor (bulan)')
                                            ->required()
                                            ->numeric()
                                            ->minValue(1)
                                            ->maxValue(72)
                                            ->step(1),

                                        Forms\Components\TextInput::make('bunga')
                                            ->label('Bunga (%)')
                                            ->required()
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->suffix('%')
                                            ->default(5.0),
                                    ])
                                    ->columns(2),

                                // Pembayaran
                                Fieldset::make('Pembayaran')
                                    ->schema([
                                        Forms\Components\TextInput::make('dp_minimal')
                                            ->label('DP Minimal')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->minValue(0)
                                            ->default(function ($get) {
                                                $hargaCash = $get('../../harga_cash');
                                                return $hargaCash ? $hargaCash * 0.2 : 0;
                                            })
                                            ->live(),

                                        Forms\Components\TextInput::make('angsuran_per_bulan')
                                            ->label('Angsuran per Bulan')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->minValue(0)
                                            ->live(),
                                    ])
                                    ->columns(2),

                                // Biaya Tambahan
                                Fieldset::make('Biaya Tambahan')
                                    ->schema([
                                        Forms\Components\TextInput::make('biaya_admin')
                                            ->label('Biaya Admin')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(1000000)
                                            ->live(),

                                        Forms\Components\TextInput::make('biaya_asuransi')
                                            ->label('Biaya Asuransi')
                                            ->required()
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->default(2000000)
                                            ->live(),
                                    ])
                                    ->columns(2),

                                // Kalkulasi
                                Forms\Components\View::make('filament.components.kalkulasi-kredit')
                                    ->extraAttributes(function (callable $get) {
                                        $tenor = $get('tenor');
                                        $angsuranPerBulan = $get('angsuran_per_bulan');
                                        $dpMinimal = $get('dp_minimal');
                                        $biayaAdmin = $get('biaya_admin');
                                        $biayaAsuransi = $get('biaya_asuransi');

                                        if ($tenor && $angsuranPerBulan) {
                                            $totalAngsuran = $angsuranPerBulan * $tenor;
                                            $totalBiaya = $totalAngsuran + $dpMinimal + $biayaAdmin + $biayaAsuransi;

                                            return [
                                                'data-tenor' => $tenor,
                                                'data-angsuran-per-bulan' => $angsuranPerBulan,
                                                'data-dp-minimal' => $dpMinimal,
                                                'data-biaya-admin' => $biayaAdmin,
                                                'data-biaya-asuransi' => $biayaAsuransi,
                                                'data-total-angsuran' => $totalAngsuran,
                                                'data-total-biaya' => $totalBiaya,
                                            ];
                                        }

                                        return [];
                                    })
                            ])
                            ->itemLabel(function (array $state): ?string {
                                $tenor = $state['tenor'] ?? null;
                                $angsuran = $state['angsuran_per_bulan'] ?? null;

                                if ($tenor && $angsuran) {
                                    return "Tenor {$tenor} Bulan - Angsuran Rp " . number_format($angsuran, 0, ',', '.');
                                }

                                return 'Tenor Baru';
                            })
                            ->collapsible()
                            ->defaultItems(3)
                            ->createItemButtonLabel('Tambah Opsi Tenor'),
                    ]);
            }
        }

        // Tampilkan preview data hanya dalam mode create
        if (!$record) {
            $schema[] = Section::make('Preview Data')
                ->schema([
                    Forms\Components\View::make('filament.components.opsi-pembayaran-preview'),
                ])
                ->collapsible()
                ->collapsed(false);
        }

        return $form->schema($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mobil.nama_mobil')
                    ->label('Mobil')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('metode')
                    ->label('Metode Pembayaran')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Cash' => 'success',
                        'Kredit' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tenor')
                    ->label('Tenor')
                    ->formatStateUsing(fn($state) => $state ? "{$state} bulan" : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga')
                    ->label('Harga')
                    ->money('IDR')
                    ->sortable(),

                Tables\Columns\TextColumn::make('dp_minimal')
                    ->label('DP Minimal')
                    ->money('IDR')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('angsuran_per_bulan')
                    ->label('Angsuran/Bulan')
                    ->money('IDR')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('bunga')
                    ->label('Bunga')
                    ->formatStateUsing(fn($state) => $state ? "{$state}%" : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mobil_id')
                    ->label('Mobil')
                    ->relationship('mobil', 'nama_mobil')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('metode')
                    ->label('Metode Pembayaran')
                    ->options([
                        'Cash' => 'Cash',
                        'Kredit' => 'Kredit',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultGroup('mobil.nama_mobil')
            ->groups([
                'mobil.nama_mobil',
                'metode',
            ]);
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
            'index' => Pages\ListManajemenOpsiPembayarans::route('/'),
            'create' => Pages\CreateManajemenOpsiPembayaran::route('/create'),
            'edit' => Pages\EditManajemenOpsiPembayaran::route('/{record}/edit'),
        ];
    }
}