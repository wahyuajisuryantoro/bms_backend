<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources;

use App\Filament\Clusters\MasterDataMobil;
use App\Filament\Clusters\MasterDataMobil\Resources\KapasitasMesinResource\Pages;
use App\Filament\Clusters\MasterDataMobil\Resources\KapasitasMesinResource\RelationManagers;
use App\Models\KapasitasMesin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KapasitasMesinResource extends Resource
{
    protected static ?string $model = KapasitasMesin::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static ?string $cluster = MasterDataMobil::class;
    protected static ?string $navigationLabel = 'Kapasitas Mesin';
    protected static ?int $navigationSort = 5;
    protected static ?string $modelLabel = 'Kapasitas Mesin';
    protected static ?string $pluralModelLabel = 'Kapasitas Mesin';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kapasitas')
                    ->required()
                    ->label('Kapasitas (cc)')
                    ->placeholder('Masukkan kapasitas mesin dalam cc')
                    ->numeric()
                    ->minValue(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('kapasitas')
                    ->label('Kapasitas (cc)')
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
                    ->before(function (KapasitasMesin $record, Tables\Actions\DeleteAction $action) {
                        if ($record->mobil()->count() > 0) {
                            $action->cancel();
                            $action->failureNotification()?->title('Tidak dapat dihapus!');
                            $action->failureNotification()?->body('Kapasitas mesin ini memiliki mobil terkait.');
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
            'index' => Pages\ListKapasitasMesins::route('/'),
            'create' => Pages\CreateKapasitasMesin::route('/create'),
            'edit' => Pages\EditKapasitasMesin::route('/{record}/edit'),
        ];
    }
}
