<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ManajemenPengguna;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ManajemenPenggunaResource\Pages;
use App\Filament\Resources\ManajemenPenggunaResource\RelationManagers;

class ManajemenPenggunaResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Manajemen Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?string $slug = 'manajemen-pengguna';
    protected static ?int $navigationSort = 5;

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn($query) => $query->where('role', 'user')->with(['userDetail.province', 'userDetail.regency', 'userDetail.district', 'userDetail.village']))
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('userDetail.no_wa')
                    ->label('No. WhatsApp')
                    ->searchable()
                    ->placeholder('Tidak ada data')
                    ->copyable(),
                TextColumn::make('userDetail.regency.name')
                    ->label('Kota/Kabupaten')
                    ->searchable()
                    ->placeholder('Tidak ada data'),

                IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-mark')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Tanggal Daftar')
                    ->formatStateUsing(function ($state) {
                        $months = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        ];
                        return $state->format('d') . ' ' . $months[$state->format('n')] . ' ' . $state->format('Y');
                    })
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([

                SelectFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->options([
                        'verified' => 'Verified',
                        'unverified' => 'Unverified',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'verified') {
                            return $query->whereNotNull('email_verified_at');
                        } elseif ($data['value'] === 'unverified') {
                            return $query->whereNull('email_verified_at');
                        }
                        return $query;
                    }),
            ])
            ->actions([
              Tables\Actions\ViewAction::make()
              
                    ->mutateRecordDataUsing(function (array $data): array {
                        $user = User::with([
                            'userDetail',
                            'userDetail.province',
                            'userDetail.regency', 
                            'userDetail.district',
                            'userDetail.village'
                        ])->find($data['id']);
                        
                        return $user ? $user->toArray() : $data;
                    })
                    ->infolist([
                        Section::make('Informasi User')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Nama Lengkap'),
                                
                                TextEntry::make('email')
                                    ->label('Email')
                                    ->copyable(),
                                
                                TextEntry::make('userDetail.no_wa')
                                    ->label('No. WhatsApp')
                                    ->copyable()
                                    ->placeholder('Tidak ada data'),
                            ])
                            ->columns(2),

                        Section::make('Detail Alamat')
                            ->schema([
                                TextEntry::make('userDetail.alamat_lengkap')
                                    ->label('Alamat Lengkap')
                                    ->placeholder('Tidak ada data')
                                    ->columnSpanFull(),
                                
                                TextEntry::make('userDetail.dusun')
                                    ->label('Dusun')
                                    ->placeholder('Tidak ada data'),
                                
                                TextEntry::make('userDetail.kode_pos')
                                    ->label('Kode Pos')
                                    ->placeholder('Tidak ada data'),
                                
                                TextEntry::make('userDetail.province.name')
                                    ->label('Provinsi')
                                    ->placeholder('Tidak ada data'),
                                
                                TextEntry::make('userDetail.regency.name')
                                    ->label('Kabupaten/Kota')
                                    ->placeholder('Tidak ada data'),
                                
                                TextEntry::make('userDetail.district.name')
                                    ->label('Kecamatan')
                                    ->placeholder('Tidak ada data'),
                                
                                TextEntry::make('userDetail.village.name')
                                    ->label('Desa/Kelurahan')
                                    ->placeholder('Tidak ada data'),
                            ])
                            ->columns(3),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus User')
                    ->modalDescription('Apakah Anda yakin ingin menghapus user ini? Data transaksi yang terkait akan ikut terhapus.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus User Terpilih')
                        ->modalDescription('Apakah Anda yakin ingin menghapus user yang dipilih? Data transaksi yang terkait akan ikut terhapus.')
                        ->modalSubmitActionLabel('Ya, Hapus Semua'),
                ]),
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
            'index' => Pages\ListManajemenPenggunas::route('/'),
            'create' => Pages\CreateManajemenPengguna::route('/create'),
            // 'edit' => Pages\EditManajemenPengguna::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return true;
    }

}
