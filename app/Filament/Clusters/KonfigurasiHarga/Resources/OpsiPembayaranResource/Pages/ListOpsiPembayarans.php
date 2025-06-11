<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource\Pages;

use App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOpsiPembayarans extends ListRecords
{
    protected static string $resource = OpsiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
