<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources;

use App\Filament\Clusters\MasterDataMobil;
use App\Filament\Clusters\MasterDataMobil\Resources\MerkMobilResource\Pages;
use App\Filament\Clusters\MasterDataMobil\Resources\MerkMobilResource\RelationManagers;
use App\Models\MerkMobil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MerkMobilResource extends Resource
{
    protected static ?string $model = MerkMobil::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark';
    protected static ?string $cluster = MasterDataMobil::class;
    protected static ?string $navigationLabel = 'Merk Mobil';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_merk')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Merk')
                    ->placeholder('Masukkan nama merk mobil')
                    ->unique(ignoreRecord: true),
                Forms\Components\FileUpload::make('foto_merk')
                    ->required()
                    ->image()
                    ->imageResizeMode('contain')
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeTargetWidth('200')
                    ->imageResizeTargetHeight('200')
                    ->directory('merk-mobil')
                    ->visibility('public')
                    ->label('Logo Merk')
                    ->helperText('Upload logo merk mobil (format: jpg, png, svg, max: 2MB)'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('foto_merk')
                    ->label('Logo')
                    ->circular()
                    ->width(50)
                    ->height(50),
                Tables\Columns\TextColumn::make('nama_merk')
                    ->label('Nama Merk')
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
                Tables\Columns\TextColumn::make('mobils_count')
                    ->label('Jumlah Mobil')
                    ->counts('mobils')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(function (MerkMobil $record, Tables\Actions\DeleteAction $action) {
                        if ($record->mobils()->count() > 0) {
                            $action->cancel();
                            $action->failureNotification()?->title('Tidak dapat dihapus!');
                            $action->failureNotification()?->body('Merk ini memiliki mobil terkait.');
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
            'index' => Pages\ListMerkMobils::route('/'),
            'create' => Pages\CreateMerkMobil::route('/create'),
            'edit' => Pages\EditMerkMobil::route('/{record}/edit'),
        ];
    }
}
