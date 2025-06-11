<?php

namespace App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource\Pages;

use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Clusters\KonfigurasiHarga\Resources\KonfigurasiKreditResource;

class CreateKonfigurasiKredit extends CreateRecord
{
    protected static string $resource = KonfigurasiKreditResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Template kredit berhasil dibuat!';
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Convert tenor_bunga_items ke format JSON yang dibutuhkan
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

     protected function afterCreate(): void
    {
        $record = $this->record;
        
        Notification::make()
            ->title('Template siap digunakan!')
            ->body("Template '{$record->nama_template}' dengan " . count($record->tenor_tersedia) . " tenor telah dibuat")
            ->success()
            ->send();
    }
}
