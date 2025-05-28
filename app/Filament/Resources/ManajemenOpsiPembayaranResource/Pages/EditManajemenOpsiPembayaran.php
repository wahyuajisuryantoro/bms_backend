<?php

namespace App\Filament\Resources\ManajemenOpsiPembayaranResource\Pages;

use App\Filament\Resources\ManajemenOpsiPembayaranResource;
use App\Models\OpsiPembayaran;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditManajemenOpsiPembayaran extends EditRecord
{
    protected static string $resource = ManajemenOpsiPembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->record;
        
        if ($record->exists) {
            $isCash = $record->metode === 'Cash';
            

            if ($isCash) {
                $data['harga_cash'] = $record->harga;
            } else {
            }
            
            return $data;
        }
        
        return $data;
    }
    
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $isCash = $record->metode === 'Cash';
        
        if ($isCash) {
            if (isset($data['harga_cash']) && !empty($data['harga_cash'])) {
                $record->harga = floatval($data['harga_cash']);
                $record->save();
            }
        } else {
 
            $tenor = intval($data['tenor'] ?? $record->tenor);
            $angsuranPerBulan = floatval($data['angsuran_per_bulan'] ?? $record->angsuran_per_bulan);
            $dpMinimal = floatval($data['dp_minimal'] ?? $record->dp_minimal);
            $biayaAdmin = floatval($data['biaya_admin'] ?? $record->biaya_admin);
            $biayaAsuransi = floatval($data['biaya_asuransi'] ?? $record->biaya_asuransi);
            $bunga = floatval($data['bunga'] ?? $record->bunga);
            
            $totalHarga = ($angsuranPerBulan * $tenor) + $dpMinimal + $biayaAdmin + $biayaAsuransi;
            
            $record->tenor = $tenor;
            $record->harga = $totalHarga;
            $record->dp_minimal = $dpMinimal;
            $record->angsuran_per_bulan = $angsuranPerBulan;
            $record->bunga = $bunga;
            $record->biaya_admin = $biayaAdmin;
            $record->biaya_asuransi = $biayaAsuransi;
            $record->save();
        }
        
        return $record;
    }

    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}