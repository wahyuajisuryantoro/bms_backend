<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\TransmisiResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\TransmisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransmisis extends ListRecords
{
    protected static string $resource = TransmisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
