<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\TipeBodiResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\TipeBodiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipeBodi extends EditRecord
{
    protected static string $resource = TipeBodiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
