<?php

namespace App\Filament\Resources\ManajemenPenggunaResource\Pages;

use App\Filament\Resources\ManajemenPenggunaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManajemenPengguna extends EditRecord
{
    protected static string $resource = ManajemenPenggunaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
