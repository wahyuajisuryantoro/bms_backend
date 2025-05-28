<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\BahanBakarResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\BahanBakarResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBahanBakars extends ListRecords
{
    protected static string $resource = BahanBakarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
