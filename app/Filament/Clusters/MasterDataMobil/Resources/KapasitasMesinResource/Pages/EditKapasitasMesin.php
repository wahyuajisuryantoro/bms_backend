<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\KapasitasMesinResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\KapasitasMesinResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKapasitasMesin extends EditRecord
{
    protected static string $resource = KapasitasMesinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
