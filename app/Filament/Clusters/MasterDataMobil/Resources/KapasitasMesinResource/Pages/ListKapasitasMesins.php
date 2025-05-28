<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\KapasitasMesinResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\KapasitasMesinResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKapasitasMesins extends ListRecords
{
    protected static string $resource = KapasitasMesinResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
