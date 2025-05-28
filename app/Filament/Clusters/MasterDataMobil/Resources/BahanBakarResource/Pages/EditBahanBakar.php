<?php

namespace App\Filament\Clusters\MasterDataMobil\Resources\BahanBakarResource\Pages;

use App\Filament\Clusters\MasterDataMobil\Resources\BahanBakarResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBahanBakar extends EditRecord
{
    protected static string $resource = BahanBakarResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
