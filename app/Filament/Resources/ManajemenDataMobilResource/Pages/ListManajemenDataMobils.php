<?php

namespace App\Filament\Resources\ManajemenDataMobilResource\Pages;

use App\Filament\Resources\ManajemenDataMobilResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManajemenDataMobils extends ListRecords
{
    protected static string $resource = ManajemenDataMobilResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
