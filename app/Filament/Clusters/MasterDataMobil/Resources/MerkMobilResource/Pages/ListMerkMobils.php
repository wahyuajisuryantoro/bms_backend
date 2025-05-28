<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\MerkMobilResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\MerkMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMerkMobils extends ListRecords
{
    protected static string $resource = MerkMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
