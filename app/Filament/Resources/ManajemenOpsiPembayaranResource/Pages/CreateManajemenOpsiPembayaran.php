<?php

namespace App\Filament\Resources\ManajemenOpsiPembayaranResource\Pages;

use App\Models\OpsiPembayaran;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ManajemenOpsiPembayaranResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateManajemenOpsiPembayaran extends CreateRecord
{
    protected static string $resource = ManajemenOpsiPembayaranResource::class;
    
    // Pengaturan redirect setelah create
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    // Lewati validasi dan penyimpanan default dari CreateRecord
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Filter data yang diperlukan untuk penyimpanan di OpsiPembayaran
        return [
            'mobil_id' => $data['mobil_id'],
            'metode' => 'Cash', // Gunakan nilai yang valid dalam enum
            'harga' => 0,
        ];
    }
    
    protected function afterCreate(): void
    {
        $record = $this->record;
        $formData = $this->form->getState();
        $mobilId = $formData['mobil_id'];
        
        // Hapus record sementara
        if ($record->exists) {
            $record->delete();
        }
        
        $recordsCreated = 0;
        
        // Debug: Tampilkan data form
        // dd($formData);
        
        // Pastikan form data lengkap
        if (!$mobilId) {
            Notification::make()
                ->title('Error: No Mobil ID')
                ->danger()
                ->send();
            return;
        }
        
        // Simpan Harga Cash
        if (isset($formData['harga_cash']) && !empty($formData['harga_cash'])) {
            $hargaCash = floatval($formData['harga_cash']);
            
            try {
                OpsiPembayaran::create([
                    'mobil_id' => $mobilId,
                    'metode' => 'Cash',
                    'tenor' => null,
                    'harga' => $hargaCash,
                    'dp_minimal' => null,
                    'angsuran_per_bulan' => null,
                    'bunga' => null,
                    'biaya_admin' => null,
                    'biaya_asuransi' => null,
                ]);
                
                $recordsCreated++;
                
                Notification::make()
                    ->title('Harga Cash Rp ' . number_format($hargaCash, 0, ',', '.') . ' berhasil disimpan')
                    ->success()
                    ->send();
            } catch (\Exception $e) {
                Notification::make()
                    ->title('Error simpan harga cash: ' . $e->getMessage())
                    ->danger()
                    ->send();
            }
        } else {
            Notification::make()
                ->title('Harga cash tidak ditemukan atau kosong')
                ->warning()
                ->send();
        }
        
        // Simpan Opsi Kredit
        if (isset($formData['opsi_kredit']) && is_array($formData['opsi_kredit'])) {
            foreach ($formData['opsi_kredit'] as $opsi) {
                if (!isset($opsi['tenor']) || empty($opsi['tenor']) || !isset($opsi['angsuran_per_bulan']) || empty($opsi['angsuran_per_bulan'])) {
                    continue;
                }
                
                $tenor = intval($opsi['tenor']);
                $angsuranPerBulan = floatval($opsi['angsuran_per_bulan']);
                $dpMinimal = floatval($opsi['dp_minimal'] ?? 0);
                $biayaAdmin = floatval($opsi['biaya_admin'] ?? 0);
                $biayaAsuransi = floatval($opsi['biaya_asuransi'] ?? 0);
                $bunga = floatval($opsi['bunga'] ?? 0);
                
                $totalHarga = ($angsuranPerBulan * $tenor) + $dpMinimal + $biayaAdmin + $biayaAsuransi;
                
                try {
                    OpsiPembayaran::create([
                        'mobil_id' => $mobilId,
                        'metode' => 'Kredit',
                        'tenor' => $tenor,
                        'harga' => $totalHarga,
                        'dp_minimal' => $dpMinimal,
                        'angsuran_per_bulan' => $angsuranPerBulan,
                        'bunga' => $bunga,
                        'biaya_admin' => $biayaAdmin,
                        'biaya_asuransi' => $biayaAsuransi,
                    ]);
                    
                    $recordsCreated++;
                } catch (\Exception $e) {
                    Notification::make()
                        ->title('Error simpan opsi kredit: ' . $e->getMessage())
                        ->danger()
                        ->send();
                }
            }
        }
        
        if ($recordsCreated > 0) {
            Notification::make()
                ->title("Berhasil menyimpan $recordsCreated opsi pembayaran")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Tidak ada opsi pembayaran yang disimpan')
                ->warning()
                ->send();
        }
    }
}