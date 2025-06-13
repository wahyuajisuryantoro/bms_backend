<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Mobil;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\TransaksiPenjualan;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransaksiPenjualanResource\Pages;
use App\Filament\Resources\TransaksiPenjualanResource\RelationManagers;

class TransaksiPenjualanResource extends Resource
{
    protected static ?string $model = TransaksiPenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Transaksi Penjualan';

    protected static ?string $modelLabel = 'Transaksi';

    protected static ?string $pluralModelLabel = 'Transaksi Penjualan';
     protected static ?string $navigationGroup = 'Manajemen Transaksi';
     protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Customer')
                    ->options(User::where('role', 'user')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->columnSpanFull(),

                Select::make('mobil_id')
                    ->label('Mobil')
                    ->options(Mobil::where('tersedia', true)->get()->pluck('nama_mobil', 'id'))
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set, $state) {
                        if ($state) {
                            $mobil = Mobil::find($state);
                            if ($mobil) {
                                $set('total_transaksi', $mobil->harga);
                            }
                        }
                    })
                    ->columnSpanFull(),

                Select::make('metode_pembelian')
                    ->label('Metode Pembelian')
                    ->options([
                        'cash' => 'Cash',
                        'kredit' => 'Kredit',
                    ])
                    ->required()
                    ->default('cash'),

                TextInput::make('total_transaksi')
                    ->label('Total Transaksi')
                    ->numeric()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->required()
                    ->formatStateUsing(fn($state) => number_format($state, 0, ',', '.'))
                    ->dehydrateStateUsing(fn($state) => (float) str_replace(['.', ','], ['', '.'], $state)),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mobil.nama_mobil')
                    ->label('Mobil')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('metode_pembelian')
                    ->label('Metode')
                    ->colors([
                        'success' => 'cash',
                        'warning' => 'kredit',
                    ])
                    ->icons([
                        'heroicon-m-banknotes' => 'cash',
                        'heroicon-m-credit-card' => 'kredit',
                    ]),

                TextColumn::make('total_transaksi')
                    ->label('Total')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                  SelectFilter::make('metode_pembelian')
                    ->label('Metode Pembelian')
                    ->options([
                        'cash' => 'Cash',
                        'kredit' => 'Kredit',
                    ]),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTransaksiPenjualans::route('/'),
            'create' => Pages\CreateTransaksiPenjualan::route('/create'),
            'edit' => Pages\EditTransaksiPenjualan::route('/{record}/edit'),
        ];
    }
}
