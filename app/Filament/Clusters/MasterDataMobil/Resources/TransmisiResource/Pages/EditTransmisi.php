<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\TransmisiResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\TransmisiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransmisi extends EditRecord
{
    protected static string $resource = TransmisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
