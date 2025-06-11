<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource\Pages;

use App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKonfigurasiKredits extends ListRecords
{
    protected static string $resource = KonfigurasiKreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
