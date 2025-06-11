<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource\Pages;

use Filament\Actions;
use App\Models\OpsiPembayaran;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\KonfigurasiHarga\Resources\OpsiPembayaranResource;

class CreateOpsiPembayaran extends CreateRecord
{
     protected static string $resource = OpsiPembayaranResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
