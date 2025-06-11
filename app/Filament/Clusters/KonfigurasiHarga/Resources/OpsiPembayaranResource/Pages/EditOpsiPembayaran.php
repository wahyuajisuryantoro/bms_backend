<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource\Pages;

use App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpsiPembayaran extends EditRecord
{
   protected static string $resource = OpsiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}