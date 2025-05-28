<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources;

use App\Filament\Clusters\MasterDataMobil;
use App\Filament\Clusters\MasterDataMobil\Resources\BahanBakarResource\Pages;
use App\Filament\Clusters\MasterDataMobil\Resources\BahanBakarResource\RelationManagers;
use App\Models\BahanBakar;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BahanBakarResource extends Resource
{
    protected static ?string $model = BahanBakar::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?string $cluster = MasterDataMobil::class;
    protected static ?string $navigationLabel = 'Bahan Bakar';
    protected static ?int $navigationSort = 4;
    protected static ?string $modelLabel = 'Bahan Bakar';
    protected static ?string $pluralModelLabel = 'Bahan Bakar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('jenis_bahan_bakar')
                    ->required()
                    ->maxLength(255)
                    ->label('Jenis Bahan Bakar')
                    ->placeholder('Masukkan jenis bahan bakar')
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
                Tables\Columns\TextColumn::make('jenis_bahan_bakar')
                    ->label('Jenis Bahan Bakar')
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
                Tables\Actions\DeleteAction::make()
                    ->before(function (BahanBakar $record, Tables\Actions\DeleteAction $action) {
                        if ($record->mobil()->count() > 0) {
                            $action->cancel();
                            $action->failureNotification()?->title('Tidak dapat dihapus!');
                            $action->failureNotification()?->body('Bahan bakar ini memiliki mobil terkait.');
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
            'index' => Pages\ListBahanBakars::route('/'),
            'create' => Pages\CreateBahanBakar::route('/create'),
            'edit' => Pages\EditBahanBakar::route('/{record}/edit'),
        ];
    }
}
