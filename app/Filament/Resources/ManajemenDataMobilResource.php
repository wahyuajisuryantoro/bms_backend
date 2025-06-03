<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ManajemenDataMobilResource\Pages;
use App\Filament\Resources\ManajemenDataMobilResource\RelationManagers;
use App\Models\Mobil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;

class ManajemenDataMobilResource extends Resource
{
    protected static ?string $model = Mobil::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Mobil';

    protected static ?string $navigationGroup = 'Data Mobil';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama_mobil')
                            ->required()
                            ->maxLength(255)
                            ->label('Nama Mobil')
                            ->placeholder('Masukkan nama mobil'),
                        Forms\Components\Select::make('merk_id')
                            ->required()
                            ->relationship('merk', 'nama_merk')
                            ->label('Merk Mobil')
                            ->placeholder('Pilih merk mobil'),
                        Forms\Components\Select::make('transmisi_id')
                            ->required()
                            ->relationship('transmisi', 'jenis_transmisi')
                            ->label('Transmisi')
                            ->placeholder('Pilih jenis transmisi'),
                        Forms\Components\Select::make('tipe_bodi_id')
                            ->required()
                            ->relationship('tipeBodi', 'nama_tipe')
                            ->label('Tipe Bodi')
                            ->placeholder('Pilih tipe bodi'),
                        Forms\Components\Select::make('bahan_bakar_id')
                            ->required()
                            ->relationship('bahanBakar', 'jenis_bahan_bakar')
                            ->label('Bahan Bakar')
                            ->placeholder('Pilih jenis bahan bakar'),
                        Forms\Components\Select::make('kapasitas_mesin_id')
                            ->required()
                            ->relationship('kapasitasMesin', 'kapasitas')
                            ->label('Kapasitas Mesin (cc)')
                            ->placeholder('Pilih kapasitas mesin'),
                        Forms\Components\Select::make('warna')
                            ->required()
                            ->relationship('warna', 'nama_warna')
                            ->label('Warna Mobil')
                            ->placeholder('Pilih warna mobil'),
                        Forms\Components\TextInput::make('tahun_keluaran')
                            ->required()
                            ->label('Tahun Keluaran')
                            ->placeholder('Masukkan tahun keluaran')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(date('Y')),
                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi')
                            ->placeholder('Masukkan deskripsi mobil')
                            ->rows(3),
                        Forms\Components\Toggle::make('tersedia')
                            ->label('Tersedia')
                            ->default(true),
                        Forms\Components\FileUpload::make('thumbnail_foto')
                            ->label('Foto Utama Mobil')
                            ->helperText('Foto ini menjadi display utama Mobil. Sebaiknya gunakan gambar dengan background transparan atau putih.')
                            ->required()
                            ->image()
                            ->imageResizeMode('contain') 
                            ->imageResizeTargetWidth(800) 
                            ->imageResizeTargetHeight(700)
                            ->directory('thumbnail-mobil')
                            ->visibility('public'),
                    ])
                    ->columns(2),
                Forms\Components\Section::make('Unggah Foto Mobil')
                    ->schema([
                        Forms\Components\Repeater::make('fotoDetail')
                            ->label('Foto Detail Mobil')
                            ->relationship()
                            ->maxItems(7)
                            ->schema([
                                Forms\Components\FileUpload::make('foto_path')
                                    ->required()
                                    ->label('Foto')
                                    ->image()
                                    ->imageResizeMode('contain')
                                    ->imageCropAspectRatio('1:1')
                                    ->imageResizeTargetWidth(400)
                                    ->imageResizeTargetHeight(400)
                                    ->directory('foto-detail-mobil')
                                    ->visibility('public'),
                            ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('thumbnail_foto')
                    ->label('Foto Mobil')
                    ->sortable()
                    ->size(50),
                Tables\Columns\TextColumn::make('nama_mobil')
                    ->label('Nama Mobil')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('merk.nama_merk')
                    ->label('Merk Mobil')
                    ->sortable(),
                Tables\Columns\TextColumn::make('tahun_keluaran')
                    ->label('Tahun Keluaran')
                    ->sortable()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('merk_id')
                    ->relationship('merk', 'nama_merk'),
                Tables\Filters\SelectFilter::make('tahun_keluaran')
                    ->options(
                        Mobil::select('tahun_keluaran')
                            ->distinct()
                            ->pluck('tahun_keluaran')
                            ->sort()
                            ->mapWithKeys(fn($year) => [$year => $year])
                    ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (Mobil $record, Tables\Actions\DeleteAction $action) {
                        if ($record->fotoDetail()->count() > 0) {
                            $action->cancel();
                            $action->failureNotification()->title('Tidak dapat dihapus!');
                            $action->failureNotification()->body('Mobil ini memiliki foto detail terkait. Hapus foto detail terlebih dahulu sebelum menghapus mobil.');
                            return;
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListManajemenDataMobils::route('/'),
            'create' => Pages\CreateManajemenDataMobil::route('/create'),
            'edit' => Pages\EditManajemenDataMobil::route('/{record}/edit'),
        ];
    }
}