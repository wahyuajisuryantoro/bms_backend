<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources;

use App\Filament\Clusters\MasterDataMobil;
use App\Filament\Clusters\MasterDataMobil\Resources\TransmisiResource\Pages;
use App\Filament\Clusters\MasterDataMobil\Resources\TransmisiResource\RelationManagers;
use App\Models\Transmisi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransmisiResource extends Resource
{
    protected static ?string $model = Transmisi::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $cluster = MasterDataMobil::class;
    protected static ?string $navigationLabel = 'Transmisi';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_transmisi')
                    ->required()
                    ->maxLength(255)
                    ->label('Jenis Transmisi')
                    ->placeholder('Masukkan jenis transmisi')
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_transmisi')
                    ->label('Jenis Transmisi')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diupdate Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mobil_count')
                    ->label('Jumlah Mobil')
                    ->counts('mobil')
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListTransmisis::route('/'),
            'create' => Pages\CreateTransmisi::route('/create'),
            'edit' => Pages\EditTransmisi::route('/{record}/edit'),
        ];
    }
}
