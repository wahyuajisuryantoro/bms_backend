<?php

namespace App\Filament\Resources\ManajemenPenggunaResource\Pages;

use App\Filament\Resources\ManajemenPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManajemenPenggunas extends ListRecords
{
    protected static string $resource = ManajemenPenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
