<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\WarnaResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\WarnaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWarna extends EditRecord
{
    protected static string $resource = WarnaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
