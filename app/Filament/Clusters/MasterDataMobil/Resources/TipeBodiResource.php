<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources;

use App\Filament\Clusters\MasterDataMobil;
use App\Filament\Clusters\MasterDataMobil\Resources\TipeBodiResource\Pages;
use App\Filament\Clusters\MasterDataMobil\Resources\TipeBodiResource\RelationManagers;
use App\Models\TipeBodi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TipeBodiResource extends Resource
{
    protected static ?string $model = TipeBodi::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $cluster = MasterDataMobil::class;
    protected static ?string $navigationLabel = 'Tipe Bodi';
    protected static ?int $navigationSort = 3;
    protected static ?string $modelLabel = 'Tipe Bodi';
    protected static ?string $pluralModelLabel = 'Tipe Bodi';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_tipe')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Tipe')
                    ->placeholder('Masukkan nama tipe bodi')
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
                Tables\Columns\TextColumn::make('nama_tipe')
                    ->label('Nama Tipe')
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
                    ->before(function (TipeBodi $record, Tables\Actions\DeleteAction $action) {
                        if ($record->mobil()->count() > 0) {
                            $action->cancel();
                            $action->failureNotification()?->title('Tidak dapat dihapus!');
                            $action->failureNotification()?->body('Tipe bodi ini memiliki mobil terkait.');
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
            'index' => Pages\ListTipeBodis::route('/'),
            'create' => Pages\CreateTipeBodi::route('/create'),
            'edit' => Pages\EditTipeBodi::route('/{record}/edit'),
        ];
    }
}
