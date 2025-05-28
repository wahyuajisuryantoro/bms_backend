<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\TipeBodiResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\TipeBodiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipeBodis extends ListRecords
{
    protected static string $resource = TipeBodiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
