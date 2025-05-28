<?php

namespace App\Filament\Resources\ManajemenDataMobilResource\Pages;

use App\Filament\Resources\ManajemenDataMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManajemenDataMobil extends EditRecord
{
    protected static string $resource = ManajemenDataMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
