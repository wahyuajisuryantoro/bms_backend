<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource;

class EditKonfigurasiKredit extends EditRecord
{
    protected static string $resource = KonfigurasiKreditResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function () {
                    if ($this->record->opsiPembayaran()->count() > 0) {
                        Notification::make()
                            ->title('Tidak dapat menghapus')
                            ->body('Template ini sedang digunakan oleh ' . $this->record->opsiPembayaran()->count() . ' mobil')
                            ->danger()
                            ->send();
                        
                        return false;
                    }
                }),
        ];
    }
      protected function getSavedNotificationTitle(): ?string
    {
        return 'Template kredit berhasil diperbarui!';
    }

     protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert tenor_bunga_config ke format untuk form
        $tenorBungaItems = [];
        
        if (isset($data['tenor_bunga_config']) && is_array($data['tenor_bunga_config'])) {
            foreach ($data['tenor_bunga_config'] as $tenor => $bunga) {
                $tenorBungaItems[] = [
                    'tenor' => $tenor,
                    'bunga' => $bunga
                ];
            }
        }

        $data['tenor_bunga_items'] = $tenorBungaItems;
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $tenorBungaConfig = [];
        
        if (isset($data['tenor_bunga_items']) && is_array($data['tenor_bunga_items'])) {
            foreach ($data['tenor_bunga_items'] as $item) {
                if (isset($item['tenor']) && isset($item['bunga'])) {
                    $tenorBungaConfig[$item['tenor']] = (float)$item['bunga'];
                }
            }
        }

        ksort($tenorBungaConfig);
        
        $data['tenor_bunga_config'] = $tenorBungaConfig;
        unset($data['tenor_bunga_items']);

        return $data;
    }
}
