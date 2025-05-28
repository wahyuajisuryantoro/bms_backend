<?php

namespace App\Filament\Resources\ManajemenOpsiPembayaranResource\Pages;

use App\Filament\Resources\ManajemenOpsiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManajemenOpsiPembayarans extends ListRecords
{
    protected static string $resource = ManajemenOpsiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
